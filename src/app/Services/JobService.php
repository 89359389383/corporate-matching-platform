<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Facades\DB;

class JobService
{
    /**
     * 企業案件を新規登録する
     *
     * 設計根拠（JobService 詳細設計）
     * - 企業に紐づく案件を作成する
     * - status（draft/published相当）の制御を行う前提
     * - Controllerは入口に徹し、保存ロジックはServiceへ集約する
     */
    public function store(int $companyId, array $validated): Job
    {
        // 今はjobsだけだが、将来の拡張に備えてトランザクションでまとめる
        return DB::transaction(function () use ($companyId, $validated): Job {
            // セキュリティ対策：company_idは認証済みの企業IDから設定する
            // 理由：リクエストに含まれるcompany_idは改ざんされる可能性があるため
            $payload = $validated;
            // リクエストにcompany_idが含まれていても、認証済みのcompanyIdで上書きする
            // これにより、他の企業のIDを指定して案件を作成する攻撃を防ぐ
            // 例：悪意のあるユーザーがリクエストボディに{"company_id": 999, "title": "..."}を送信した場合
            // この対策がないと、企業ID 999の名義で勝手に案件を作成できてしまう危険性がある
            $payload['company_id'] = $companyId;

            // jobs にINSERTして案件を作成する
            return Job::create($payload);
        });
    }

    /**
     * 企業案件を更新する
     *
     * 設計根拠（JobService 詳細設計）
     * - 既存案件の更新（status変更も含む）を行う
     * - 途中失敗時に不整合を残さないため、トランザクションで更新する
     */
    public function update(Job $job, array $validated): Job
    {
        // 更新もまとめて安全に行う
        return DB::transaction(function () use ($job, $validated): Job {
            // jobs を上書き更新する（fillableの範囲で反映される）
            $job->fill($validated)->save();

            // 更新後のjobを返す
            return $job;
        });
    }
}

