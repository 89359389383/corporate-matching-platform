<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Thread;
use App\Services\ContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CorporateContractController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        $contracts = Contract::query()
            ->where('corporate_id', $corporate->id)
            ->with(['thread', 'company', 'job', 'signatures'])
            ->orderByDesc('id')
            ->paginate(20);

        $unreadScoutCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $unreadApplicationCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $userInitial = 'U';
        if (!empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.contracts.index', [
            'contracts' => $contracts,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }

    public function threadIndex(Request $request, Thread $thread)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int)$thread->corporate_id !== (int)$corporate->id) {
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

        $unreadScoutCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $unreadApplicationCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $userInitial = 'U';
        if (!empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.contracts.thread_index', [
            'thread' => $thread->loadMissing(['company', 'job']),
            'contracts' => $contracts,
            'selectedStatus' => $selectedStatus,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }

    public function show(Contract $contract)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int)$contract->corporate_id !== (int)$corporate->id) {
            abort(403);
        }

        $contract->loadMissing(['thread', 'company', 'corporate', 'job', 'signatures', 'changeRequests', 'auditLogs']);

        $unreadScoutCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $unreadApplicationCount = Thread::query()
            ->where('corporate_id', $corporate->id)
            ->whereNotNull('job_id')
            ->where('is_unread_for_corporate', true)
            ->count();

        $userInitial = 'U';
        if (!empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.contracts.show', [
            'contract' => $contract,
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
            'corporate' => $corporate,
        ]);
    }

    public function return(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int)$contract->corporate_id !== (int)$corporate->id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $contractService->returnByCorporate($contract, $user->role, (int)$corporate->id, $validated['body'], $request->ip(), $request->userAgent());

        return redirect()
            ->route('corporate.contracts.show', ['contract' => $contract])
            ->with('success', '差し戻しました');
    }

    public function agree(Request $request, Contract $contract, ContractService $contractService)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int)$contract->corporate_id !== (int)$corporate->id) {
            abort(403);
        }

        $contractService->agreeByCorporate($contract, $user->role, (int)$corporate->id, $request->ip(), $request->userAgent());

        return redirect()
            ->route('corporate.contracts.show', ['contract' => $contract])
            ->with('success', '同意しました（企業の同意待ち）');
    }

    public function pdf(Contract $contract)
    {
        $user = Auth::user();
        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate === null) {
            return redirect('/corporate/profile')->with('error', '先にプロフィール登録が必要です');
        }

        if ((int)$contract->corporate_id !== (int)$corporate->id) {
            abort(403);
        }

        if ($contract->pdf_path === null) {
            return redirect()
                ->route('corporate.contracts.show', ['contract' => $contract])
                ->with('error', 'PDFは締結後に生成されます');
        }

        if (!Storage::disk('local')->exists($contract->pdf_path)) {
            return redirect()
                ->route('corporate.contracts.show', ['contract' => $contract])
                ->with('error', 'PDFファイルが見つかりません');
        }

        return Storage::disk('local')->download($contract->pdf_path, 'contract_' . $contract->id . '_v' . $contract->version . '.pdf');
    }
}

