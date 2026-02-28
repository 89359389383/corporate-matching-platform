<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyContractUpsertRequest;
use App\Models\Contract;
use App\Models\Thread;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyContractController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }

        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        $contracts = Contract::query()
            ->where('company_id', $company->id)
            ->with(['thread', 'corporate', 'job', 'signatures'])
            ->orderByDesc('id')
            ->paginate(20);

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        return view('company.contracts.index', [
            'contracts' => $contracts,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function threadIndex(Request $request, Thread $thread, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$thread->company_id !== (int)$company->id) {
            abort(403);
        }

        $allowedStatuses = [
            Contract::STATUS_DRAFT,
            Contract::STATUS_PROPOSED,
            Contract::STATUS_NEGOTIATING,
            Contract::STATUS_READY_TO_SIGN,
            Contract::STATUS_SIGNED,
            Contract::STATUS_ACTIVE,
            Contract::STATUS_COMPLETED,
            Contract::STATUS_TERMINATED,
            Contract::STATUS_ARCHIVED,
        ];

        $selectedStatus = $request->query('status');
        if ($selectedStatus !== null && $selectedStatus !== '' && !in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = null;
        }

        $contractsQuery = Contract::query()
            ->where('thread_id', $thread->id)
            ->with(['signatures'])
            ->orderByDesc('version');

        if ($selectedStatus !== null && $selectedStatus !== '') {
            $contractsQuery->where('status', $selectedStatus);
        }

        $contracts = $contractsQuery->get();

        $current = $contractService->getCurrentContract($thread);
        $canCreate = $contractService->canCreateNewContract($thread);

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        return view('company.contracts.thread_index', [
            'thread' => $thread->loadMissing(['corporate', 'job']),
            'contracts' => $contracts,
            'current' => $current,
            'canCreate' => $canCreate,
            'selectedStatus' => $selectedStatus,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function create(Thread $thread)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$thread->company_id !== (int)$company->id) {
            abort(403);
        }

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $thread->loadMissing(['corporate', 'job']);

        return view('company.contracts.create', [
            'thread' => $thread,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function store(CompanyContractUpsertRequest $request, Thread $thread, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$thread->company_id !== (int)$company->id) {
            abort(403);
        }

        $validated = $request->validated();

        $thread->loadMissing(['corporate', 'job']);

        $terms = $contractService->buildTermsFromPayload(array_merge($validated, [
            'corporate_name' => $thread->corporate->corporation_name ?: ($thread->corporate->display_name ?? ''),
            'job_title' => $thread->job ? $thread->job->title : '',
        ]));

        $contract = $contractService->createDraft(
            $thread,
            $user->role,
            (int)$company->id,
            [
                'contract_type' => $validated['contract_type'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'terms_json' => $terms,
            ],
            $request->ip(),
            $request->userAgent()
        );

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '契約（下書き）を作成しました');
    }

    public function show(Contract $contract)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }

        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }

        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contract->loadMissing(['thread', 'company', 'corporate', 'job', 'signatures', 'changeRequests', 'auditLogs']);

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        return view('company.contracts.show', [
            'contract' => $contract,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function edit(Contract $contract)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }
        if (!$contract->isEditableDraft()) {
            return redirect()
                ->route('company.contracts.show', ['contract' => $contract])
                ->with('error', '編集できるのはdraftのみです');
        }

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $contract->loadMissing(['thread', 'corporate', 'job']);

        return view('company.contracts.edit', [
            'contract' => $contract,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function update(CompanyContractUpsertRequest $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $validated = $request->validated();

        $contract->loadMissing(['thread.corporate', 'thread.job']);
        $thread = $contract->thread;

        $terms = $contractService->buildTermsFromPayload(array_merge($validated, [
            'corporate_name' => $thread && $thread->corporate ? ($thread->corporate->corporation_name ?: ($thread->corporate->display_name ?? '')) : '',
            'job_title' => $thread && $thread->job ? $thread->job->title : '',
        ]));

        $contractService->updateDraft(
            $contract,
            $user->role,
            (int)$company->id,
            [
                'contract_type' => $validated['contract_type'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'terms_json' => $terms,
            ],
            $request->ip(),
            $request->userAgent()
        );

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '契約（下書き）を更新しました');
    }

    public function propose(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contractService->propose($contract, $user->role, (int)$company->id, $request->ip(), $request->userAgent());

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '法人へ提示しました');
    }

    public function createVersion(Contract $contract)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contract->loadMissing(['thread', 'corporate', 'job']);

        $unreadApplicationCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        $unreadScoutCount = Thread::query()
            ->where('company_id', $company->id)
            ->whereNull('job_id')
            ->where('is_unread_for_company', true)
            ->count();

        return view('company.contracts.create_version', [
            'base' => $contract,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
        ]);
    }

    public function storeVersion(CompanyContractUpsertRequest $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $validated = $request->validated();

        $contract->loadMissing(['thread.corporate', 'thread.job']);
        $thread = $contract->thread;

        $terms = $contractService->buildTermsFromPayload(array_merge($validated, [
            'corporate_name' => $thread && $thread->corporate ? ($thread->corporate->corporation_name ?: ($thread->corporate->display_name ?? '')) : '',
            'job_title' => $thread && $thread->job ? $thread->job->title : '',
        ]));

        $new = $contractService->createNewVersionFrom(
            $contract,
            $user->role,
            (int)$company->id,
            [
                'contract_type' => $validated['contract_type'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'terms_json' => $terms,
            ],
            $request->ip(),
            $request->userAgent()
        );

        return redirect()
            ->route('company.contracts.edit', ['contract' => $new])
            ->with('success', '新版（下書き）を作成しました。内容を確認して提示してください。');
    }

    public function agree(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contractService->agreeByCompany($contract, $user->role, (int)$company->id, $request->ip(), $request->userAgent());

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '同意しました（締結済み）');
    }

    public function complete(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contractService->completeByCompany($contract, $user->role, (int)$company->id, $request->ip(), $request->userAgent());

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '契約を完了にしました');
    }

    public function pdf(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $contract->loadMissing(['company', 'corporate', 'job', 'thread', 'signatures']);

        // PDFが未生成 or 実体が無い場合はオンデマンド生成してからダウンロードする
        if ($contract->pdf_path === null || !Storage::disk('local')->exists($contract->pdf_path)) {
            if ($contract->pdf_path !== null && !Storage::disk('local')->exists($contract->pdf_path)) {
                $contract->forceFill(['pdf_path' => null])->save();
            }
            $docHash = $contractService->computeDocumentHash($contract);
            $contractService->generateSignedPdf($contract, $docHash);
        }

        if ($contract->pdf_path === null || !Storage::disk('local')->exists($contract->pdf_path)) {
            return redirect()
                ->route('company.contracts.show', ['contract' => $contract])
                ->with('error', 'PDFの生成に失敗しました');
        }

        return Storage::disk('local')->download($contract->pdf_path, 'contract_' . $contract->id . '_v' . $contract->version . '.pdf');
    }

    public function terminate(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'company') {
            abort(403);
        }
        $company = $user->company;
        if ($company === null) {
            return redirect('/company/profile')->with('error', '先に企業プロフィールを登録してください');
        }
        if ((int)$contract->company_id !== (int)$company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:5000',
        ]);

        $contractService->terminate($contract, $user->role, (int)$company->id, $validated['reason'], $request->ip(), $request->userAgent());

        return redirect()
            ->route('company.contracts.show', ['contract' => $contract])
            ->with('success', '契約を終了しました');
    }
}

