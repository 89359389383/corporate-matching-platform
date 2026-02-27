<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractAuditLog;
use App\Models\ContractChangeRequest;
use App\Models\ContractSignature;
use App\Models\Thread;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ContractService
{
    /**
     * @return array<string, mixed>
     */
    public function buildTermsFromPayload(array $payload): array
    {
        $ordered = [
            'corporate_name' => (string)($payload['corporate_name'] ?? ''),
            'job_title' => (string)($payload['job_title'] ?? ''),

            'contract_period' => (string)($payload['contract_period'] ?? ''),
            'trade_terms' => (string)($payload['trade_terms'] ?? ''),
            'amount' => (string)($payload['amount'] ?? ''),
            'payment_terms' => (string)($payload['payment_terms'] ?? ''),
            'deliverables' => (string)($payload['deliverables'] ?? ''),
            'due_date' => (string)($payload['due_date'] ?? ''),
            'scope' => (string)($payload['scope'] ?? ''),
            'special_terms' => (string)($payload['special_terms'] ?? ''),
            'free_text' => (string)($payload['free_text'] ?? ''),
        ];

        return $ordered;
    }

    public function computeDocumentHash(Contract $contract): string
    {
        $data = [
            'thread_id' => (int)$contract->thread_id,
            'company_id' => (int)$contract->company_id,
            'corporate_id' => (int)$contract->corporate_id,
            'job_id' => (int)($contract->job_id ?? 0),
            'contract_type' => (string)$contract->contract_type,
            'version' => (int)$contract->version,
            'start_date' => $contract->start_date ? $contract->start_date->format('Y-m-d') : null,
            'end_date' => $contract->end_date ? $contract->end_date->format('Y-m-d') : null,
            'terms' => $this->sortKeysRecursively($contract->terms_json ?? []),
        ];

        $json = json_encode(
            $this->sortKeysRecursively($data),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        return hash('sha256', (string)$json);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function sortKeysRecursively($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        $isAssoc = array_keys($value) !== range(0, count($value) - 1);
        if ($isAssoc) {
            ksort($value);
        }

        foreach ($value as $k => $v) {
            $value[$k] = $this->sortKeysRecursively($v);
        }

        return $value;
    }

    private function assertCompanyRole(string $role): void
    {
        if ($role !== 'company') {
            throw ValidationException::withMessages(['role' => '企業のみ実行可能です。']);
        }
    }

    private function assertCorporateRole(string $role): void
    {
        if ($role !== 'corporate') {
            throw ValidationException::withMessages(['role' => '法人のみ実行可能です。']);
        }
    }

    private function assertThreadParty(Thread $thread, string $actorType, int $actorOrgId): void
    {
        if ($actorType === 'company' && (int)$thread->company_id !== (int)$actorOrgId) {
            throw ValidationException::withMessages(['thread' => '当事者ではありません。']);
        }
        if ($actorType === 'corporate' && (int)$thread->corporate_id !== (int)$actorOrgId) {
            throw ValidationException::withMessages(['thread' => '当事者ではありません。']);
        }
    }

    private function writeAuditLog(Contract $contract, string $action, string $actorType, ?int $actorId, ?string $ip, ?string $userAgent, ?array $meta): void
    {
        ContractAuditLog::create([
            'contract_id' => $contract->id,
            'action' => $action,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'meta_json' => $meta,
            'occurred_at' => Carbon::now(),
        ]);
    }

    public function getCurrentContract(Thread $thread): ?Contract
    {
        return Contract::query()
            ->where('thread_id', $thread->id)
            ->whereNull('superseded_by_contract_id')
            ->orderByDesc('version')
            ->first();
    }

    public function canCreateNewContract(Thread $thread): bool
    {
        $current = $this->getCurrentContract($thread);
        if ($current === null) {
            return true;
        }

        return in_array($current->status, [Contract::STATUS_COMPLETED, Contract::STATUS_TERMINATED, Contract::STATUS_ARCHIVED], true);
    }

    /**
     * 企業のみ: draft作成（法人には通知しない）
     */
    public function createDraft(Thread $thread, string $role, int $companyId, array $data, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        $this->assertThreadParty($thread, 'company', $companyId);

        if (!$this->canCreateNewContract($thread)) {
            throw ValidationException::withMessages(['contract' => 'このスレッドでは進行中の契約があるため作成できません。']);
        }

        return DB::transaction(function () use ($thread, $companyId, $data, $ip, $ua): Contract {
            $latestVersion = (int)(Contract::query()->where('thread_id', $thread->id)->max('version') ?? 0);
            $version = $latestVersion + 1;

            $contract = Contract::create([
                'thread_id' => $thread->id,
                'company_id' => $thread->company_id,
                'corporate_id' => $thread->corporate_id,
                'job_id' => $thread->job_id,
                'contract_type' => (string)$data['contract_type'],
                'version' => $version,
                'status' => Contract::STATUS_DRAFT,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'terms_json' => $data['terms_json'],
            ]);

            $this->writeAuditLog($contract, 'create_draft', 'company', $companyId, $ip, $ua, [
                'version' => $contract->version,
                'contract_type' => $contract->contract_type,
            ]);

            return $contract;
        });
    }

    public function updateDraft(Contract $contract, string $role, int $companyId, array $data, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$contract->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if (!$contract->isEditableDraft()) {
            throw ValidationException::withMessages(['contract' => '編集できるのはdraftのみです。']);
        }

        $contract->forceFill([
            'contract_type' => (string)$data['contract_type'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'terms_json' => $data['terms_json'],
        ])->save();

        $this->writeAuditLog($contract, 'update_draft', 'company', $companyId, $ip, $ua, [
            'version' => $contract->version,
        ]);

        return $contract;
    }

    public function propose(Contract $contract, string $role, int $companyId, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$contract->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if ($contract->status !== Contract::STATUS_DRAFT) {
            throw ValidationException::withMessages(['contract' => '提示できるのはdraftのみです。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は提示できません。']);
        }

        return DB::transaction(function () use ($contract, $companyId, $ip, $ua): Contract {
            $contract->forceFill([
                'status' => Contract::STATUS_PROPOSED,
                'proposed_at' => Carbon::now(),
            ])->save();

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_corporate' => true])->save();
            }

            $this->writeAuditLog($contract, 'propose', 'company', $companyId, $ip, $ua, [
                'version' => $contract->version,
            ]);

            return $contract;
        });
    }

    public function returnByCorporate(Contract $contract, string $role, int $corporateId, string $body, ?string $ip, ?string $ua): Contract
    {
        $this->assertCorporateRole($role);
        if ((int)$contract->corporate_id !== (int)$corporateId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if ($contract->status !== Contract::STATUS_PROPOSED) {
            throw ValidationException::withMessages(['contract' => '差し戻しできるのは提示中（proposed）のみです。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は差し戻しできません。']);
        }

        $body = trim($body);
        if ($body === '') {
            throw ValidationException::withMessages(['body' => '差し戻し理由を入力してください。']);
        }

        return DB::transaction(function () use ($contract, $corporateId, $body, $ip, $ua): Contract {
            ContractChangeRequest::create([
                'contract_id' => $contract->id,
                'requester_type' => 'corporate',
                'requester_id' => $corporateId,
                'body' => $body,
            ]);

            $contract->forceFill([
                'status' => Contract::STATUS_NEGOTIATING,
            ])->save();

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_company' => true])->save();
            }

            $this->writeAuditLog($contract, 'return', 'corporate', $corporateId, $ip, $ua, [
                'version' => $contract->version,
            ]);

            return $contract;
        });
    }

    public function createNewVersionFrom(Contract $base, string $role, int $companyId, array $data, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$base->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if (!$base->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版から新版は作成できません。']);
        }
        if (!in_array($base->status, [Contract::STATUS_PROPOSED, Contract::STATUS_NEGOTIATING], true)) {
            throw ValidationException::withMessages(['contract' => '新版作成は提案後（proposed/negotiating）のみ可能です。']);
        }

        return DB::transaction(function () use ($base, $companyId, $data, $ip, $ua): Contract {
            $newVersion = $base->version + 1;

            $new = Contract::create([
                'thread_id' => $base->thread_id,
                'company_id' => $base->company_id,
                'corporate_id' => $base->corporate_id,
                'job_id' => $base->job_id,
                'contract_type' => (string)$data['contract_type'],
                'version' => $newVersion,
                'status' => Contract::STATUS_DRAFT,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'terms_json' => $data['terms_json'],
            ]);

            $base->forceFill([
                'status' => Contract::STATUS_ARCHIVED,
                'archived_at' => Carbon::now(),
                'superseded_by_contract_id' => $new->id,
            ])->save();

            $this->writeAuditLog($new, 'create_new_version', 'company', $companyId, $ip, $ua, [
                'from_contract_id' => $base->id,
                'from_version' => $base->version,
                'to_version' => $new->version,
            ]);

            return $new;
        });
    }

    public function agreeByCorporate(Contract $contract, string $role, int $corporateId, ?string $ip, ?string $ua): Contract
    {
        $this->assertCorporateRole($role);
        if ((int)$contract->corporate_id !== (int)$corporateId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if ($contract->status !== Contract::STATUS_PROPOSED) {
            throw ValidationException::withMessages(['contract' => '同意できるのは提示中（proposed）のみです。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は同意できません。']);
        }

        return DB::transaction(function () use ($contract, $corporateId, $ip, $ua): Contract {
            $docHash = $this->computeDocumentHash($contract);

            ContractSignature::updateOrCreate(
                ['contract_id' => $contract->id, 'signer_type' => 'corporate'],
                [
                    'signer_id' => $corporateId,
                    'organization_type' => 'corporate',
                    'organization_id' => $contract->corporate_id,
                    'ip' => $ip,
                    'user_agent' => $ua,
                    'signed_at' => Carbon::now(),
                    'document_hash' => $docHash,
                ]
            );

            $contract->forceFill([
                'status' => Contract::STATUS_READY_TO_SIGN,
            ])->save();

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_company' => true])->save();
            }

            $this->writeAuditLog($contract, 'agree', 'corporate', $corporateId, $ip, $ua, [
                'version' => $contract->version,
            ]);

            return $contract;
        });
    }

    public function agreeByCompany(Contract $contract, string $role, int $companyId, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$contract->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if ($contract->status !== Contract::STATUS_READY_TO_SIGN) {
            throw ValidationException::withMessages(['contract' => '企業同意は ready_to_sign のみ可能です。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は同意できません。']);
        }

        return DB::transaction(function () use ($contract, $companyId, $ip, $ua): Contract {
            $docHash = $this->computeDocumentHash($contract);

            ContractSignature::updateOrCreate(
                ['contract_id' => $contract->id, 'signer_type' => 'company'],
                [
                    'signer_id' => $companyId,
                    'organization_type' => 'company',
                    'organization_id' => $contract->company_id,
                    'ip' => $ip,
                    'user_agent' => $ua,
                    'signed_at' => Carbon::now(),
                    'document_hash' => $docHash,
                ]
            );

            $hasCompany = ContractSignature::query()
                ->where('contract_id', $contract->id)
                ->where('signer_type', 'company')
                ->exists();
            $hasCorporate = ContractSignature::query()
                ->where('contract_id', $contract->id)
                ->where('signer_type', 'corporate')
                ->exists();

            if (!$hasCompany || !$hasCorporate) {
                throw ValidationException::withMessages(['contract' => '署名が揃っていません。']);
            }

            $contract->forceFill([
                'status' => Contract::STATUS_SIGNED,
                'signed_at' => Carbon::now(),
            ])->save();

            // PDF生成は失敗しても「締結」自体は完了させる（PDF生成環境が無い場合があるため）
            try {
                $this->generateSignedPdf($contract, $docHash);
            } catch (\Throwable $e) {
                $this->writeAuditLog($contract, 'pdf_generate_failed', 'system', null, null, null, [
                    'error_class' => get_class($e),
                    'error_message' => $e->getMessage(),
                ]);
            }

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_corporate' => true])->save();
            }

            $this->writeAuditLog($contract, 'sign', 'company', $companyId, $ip, $ua, [
                'version' => $contract->version,
            ]);

            return $contract;
        });
    }

    public function generateSignedPdf(Contract $contract, string $docHash): void
    {
        if ($contract->pdf_path !== null) {
            return;
        }

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            throw ValidationException::withMessages([
                'pdf' => 'PDF生成には barryvdh/laravel-dompdf の導入が必要です（Composer + openssl拡張が必要）。',
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contracts.pdf', [
            'contract' => $contract->loadMissing(['company', 'corporate', 'job', 'thread', 'signatures']),
        ]);

        $binary = $pdf->output();
        $pdfHash = hash('sha256', $binary);

        $path = 'contracts/' . $contract->id . '/contract_v' . $contract->version . '.pdf';
        Storage::disk('local')->put($path, $binary);

        $contract->forceFill([
            'pdf_path' => $path,
            'document_hash' => $docHash,
            'pdf_hash' => $pdfHash,
        ])->save();

        $this->writeAuditLog($contract, 'pdf_generated', 'system', null, null, null, [
            'pdf_path' => $path,
        ]);
    }

    public function activateSignedContracts(): int
    {
        $today = Carbon::today();

        $targets = Contract::query()
            ->where('status', Contract::STATUS_SIGNED)
            ->whereNotNull('start_date')
            ->where('start_date', '<=', $today->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($targets as $c) {
            $c->forceFill([
                'status' => Contract::STATUS_ACTIVE,
                'active_at' => Carbon::now(),
            ])->save();
            $this->writeAuditLog($c, 'activate', 'system', null, null, null, null);
            $count++;
        }

        return $count;
    }

    public function terminate(Contract $contract, string $role, int $companyId, string $reason, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$contract->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if ($contract->status !== Contract::STATUS_ACTIVE) {
            throw ValidationException::withMessages(['contract' => '終了できるのはactiveのみです。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は終了できません。']);
        }

        $reason = trim($reason);
        if ($reason === '') {
            throw ValidationException::withMessages(['reason' => '終了理由を入力してください。']);
        }

        return DB::transaction(function () use ($contract, $companyId, $reason, $ip, $ua): Contract {
            $contract->forceFill([
                'status' => Contract::STATUS_TERMINATED,
                'terminated_at' => Carbon::now(),
            ])->save();

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_corporate' => true])->save();
            }

            $this->writeAuditLog($contract, 'terminate', 'company', $companyId, $ip, $ua, [
                'reason' => $reason,
            ]);

            return $contract;
        });
    }

    public function completeByCompany(Contract $contract, string $role, int $companyId, ?string $ip, ?string $ua): Contract
    {
        $this->assertCompanyRole($role);
        if ((int)$contract->company_id !== (int)$companyId) {
            throw ValidationException::withMessages(['contract' => '当事者ではありません。']);
        }
        if (!$contract->isCurrent()) {
            throw ValidationException::withMessages(['contract' => '旧版は完了できません。']);
        }
        if (!in_array($contract->status, [Contract::STATUS_SIGNED, Contract::STATUS_ACTIVE], true)) {
            throw ValidationException::withMessages(['contract' => '完了できるのは signed / active のみです。']);
        }

        return DB::transaction(function () use ($contract, $companyId, $ip, $ua): Contract {
            $contract->forceFill([
                'status' => Contract::STATUS_COMPLETED,
                'completed_at' => Carbon::now(),
            ])->save();

            $thread = $contract->thread;
            if ($thread) {
                $thread->forceFill(['is_unread_for_corporate' => true])->save();
            }

            $this->writeAuditLog($contract, 'complete', 'company', $companyId, $ip, $ua, [
                'version' => $contract->version,
            ]);

            return $contract;
        });
    }
}

