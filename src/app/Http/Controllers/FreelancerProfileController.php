<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FreelancerRegisterRequest;
use App\Http\Requests\FreelancerProfileUpdateRequest;
use App\Services\FreelancerProfileService;

class FreelancerProfileController extends Controller
{
    /**
     * 初回プロフィール登録画面を表示する
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'freelancer') {
            abort(403);
        }

        if ($user->freelancer()->exists()) {
            return redirect('/freelancer/jobs');
        }

        return view('freelancer.profile.create');
    }

    /**
     * プロフィール登録
     */
    public function store(FreelancerRegisterRequest $request, FreelancerProfileService $freelancerProfileService)
    {
        $user = Auth::user();

        if ($user->role !== 'freelancer') {
            abort(403);
        }

        if ($user->freelancer()->exists()) {
            return redirect('/freelancer/jobs');
        }

        $validated = $request->validated();
        $validated['icon'] = $request->file('icon');

        $freelancerProfileService->register($user, $validated);

        return redirect('/freelancer/jobs')->with('success', 'プロフィール登録が完了しました');
    }

    /**
     * プロフィール設定画面
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 'freelancer') {
            abort(403);
        }

        $freelancer = $user->freelancer;
        if ($freelancer) {
            $freelancer->load(['skills', 'customSkills', 'portfolios']);
        }

        return view('freelancer.profile.settings', [
            'freelancer' => $freelancer,
        ]);
    }

    /**
     * プロフィール設定の更新
     */
    public function update(FreelancerProfileUpdateRequest $request, FreelancerProfileService $freelancerProfileService)
    {
        $user = Auth::user();

        if ($user->role !== 'freelancer') {
            abort(403);
        }

        if (!$user->freelancer()->exists()) {
            return redirect('/freelancer/profile')->with('error', '先にプロフィールを登録してください');
        }

        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon');
        }

        $freelancerProfileService->update($user->freelancer, $validated);

        return redirect()->route('freelancer.profile.settings')->with('success', 'プロフィールを更新しました');
    }
}

