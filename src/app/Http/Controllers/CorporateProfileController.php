<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CorporateRegisterRequest;
use App\Http\Requests\CorporateProfileUpdateRequest;
use App\Models\Thread;
use App\Services\CorporateProfileService;
use App\Models\Skill;

class CorporateProfileController extends Controller
{
    /**
     * 初回プロフィール登録画面を表示する
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'corporate') {
            abort(403);
        }

        if ($user->corporate()->exists()) {
            return redirect('/corporate/jobs');
        }

        $userInitial = !empty($user->email) ? mb_substr($user->email, 0, 1) : 'U';
        return view('corporate.profile.create', [
            'user' => $user,
            'unreadApplicationCount' => 0,
            'unreadScoutCount' => 0,
            'userInitial' => $userInitial,
            'corporate' => null,
        ]);
    }

    /**
     * プロフィール登録
     */
    public function store(CorporateRegisterRequest $request, CorporateProfileService $corporateProfileService)
    {
        $user = Auth::user();

        if ($user->role !== 'corporate') {
            abort(403);
        }

        if ($user->corporate()->exists()) {
            return redirect('/corporate/jobs');
        }

        $validated = $request->validated();
        $validated['icon'] = $request->file('icon');

        $corporateProfileService->register($user, $validated);

        return redirect('/corporate/jobs')->with('success', 'プロフィール登録が完了しました');
    }

    /**
     * プロフィール設定画面
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 'corporate') {
            abort(403);
        }

        $corporate = $user->corporate;
        if ($corporate) {
            $corporate->load(['skills', 'customSkills', 'portfolios']);
        }

        // 未読スカウト数を取得（ヘッダー用）
        $unreadScoutCount = 0;
        $unreadApplicationCount = 0;
        if ($corporate !== null) {
            $unreadScoutCount = Thread::query()
                ->where('corporate_id', $corporate->id)
                ->whereNull('job_id')
                ->where('is_unread_for_corporate', true)
                ->count();

            // 未読応募数を取得（ヘッダー用）
            $unreadApplicationCount = Thread::query()
                ->where('corporate_id', $corporate->id)
                ->whereNotNull('job_id')
                ->where('is_unread_for_corporate', true)
                ->count();
        }

        // ユーザー名の最初の文字を取得（アバター表示用）
        $userInitial = 'U';
        if ($corporate !== null && !empty($corporate->display_name)) {
            $userInitial = mb_substr($corporate->display_name, 0, 1);
        } elseif (!empty($user->email)) {
            $userInitial = mb_substr($user->email, 0, 1);
        }

        return view('corporate.profile.settings', [
            'user' => $user,
            'corporate' => $corporate,
            'allSkills' => Skill::orderBy('name')->get(),
            'unreadApplicationCount' => $unreadApplicationCount,
            'unreadScoutCount' => $unreadScoutCount,
            'userInitial' => $userInitial,
        ]);
    }

    /**
     * プロフィール設定の更新
     */
    public function update(CorporateProfileUpdateRequest $request, CorporateProfileService $corporateProfileService)
    {
        $user = Auth::user();

        if ($user->role !== 'corporate') {
            abort(403);
        }

        if (!$user->corporate()->exists()) {
            return redirect('/corporate/profile')->with('error', '先にプロフィールを登録してください');
        }

        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon');
        }

        $corporateProfileService->update($user->corporate, $validated);

        return redirect()->route('corporate.profile.settings')->with('success', 'プロフィールを更新しました');
    }
}

