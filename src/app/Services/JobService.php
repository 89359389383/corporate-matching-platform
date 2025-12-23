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
            // company_id は認証企業からのみ設定する（改ざん防止）
            $payload = $validated;
            // リクエストからcompany_idが来ても上書きして安全側に倒す
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

