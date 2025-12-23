<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * ログイン画面を表示する（表示のみ）
     */
    public function showLoginForm()
    {
        // ログイン画面のBladeを返すだけ（認証処理はしない）
        return view('auth.login');
    }

    /**
     * ログイン処理を行い、role に応じて遷移先を分岐する
     */
    public function login(LoginRequest $request)
    {
        // 入力を最低限チェックする（FormRequest に委譲）
        $credentials = $request->validated();

        // メール/パスワードで認証を試みる（Auth::attempt）
        if (!Auth::attempt($credentials)) {
            // 失敗時はエラーを返してログイン画面へ戻す（email欄にまとめて表示）
            throw ValidationException::withMessages(['email' => 'メールアドレスまたはパスワードが正しくありません']);
        }

        // セッション固定化対策としてセッションIDを再生成する
        $request->session()->regenerate();

        // ログインしたユーザーを取得する（role分岐に使用）
        /** @var User $user */
        $user = Auth::user();

        // role によってトップページへリダイレクトする
        if ($user->role === 'freelancer') {
            // フリーランスは案件一覧へ
            return redirect('/freelancer/jobs');
        }

        if ($user->role === 'company') {
            // 企業はフリーランス一覧へ
            return redirect('/company/freelancers');
        }

        // 想定外のroleなら安全側に倒してログアウトし、ログインへ戻す
        Auth::logout();

        // セッションを破棄する（中途半端にログイン状態を残さない）
        $request->session()->invalidate();

        // CSRFトークンも再生成する
        $request->session()->regenerateToken();

        // ログイン画面に戻してエラーを表示する
        return redirect('/login')->withErrors(['email' => 'アカウント種別が不正です']);
    }

    /**
     * フリーランス登録画面を表示する（表示のみ）
     */
    public function showFreelancerRegister()
    {
        // 登録画面のBladeを返すだけ（DB更新はしない）
        return view('auth.register.freelancer');
    }

    /**
     * フリーランスユーザーを作成し、プロフィール入力へ遷移する
     */
    public function storeFreelancer(RegisterRequest $request)
    {
        // 入力をバリデーションする（RegisterRequest に委譲）
        $validated = $request->validated();

        // ユーザーを作成する（role=freelancer）
        $user = User::create([
            // メールアドレスを保存する
            'email' => $validated['email'],
            // パスワードをハッシュ化して保存する（平文では保存しない）
            'password' => Hash::make($validated['password']),
            // 役割をフリーランスにする
            'role' => 'freelancer',
        ]);

        // 作成したユーザーでログインさせる
        Auth::login($user);

        // プロフィール登録画面へリダイレクトする（/freelancer/profile 相当）
        return redirect('/freelancer/profile');
    }

    /**
     * 企業登録画面を表示する（表示のみ）
     */
    public function showCompanyRegister()
    {
        // 企業登録画面のBladeを返すだけ
        return view('auth.register.company');
    }

    /**
     * 企業ユーザーを作成し、企業プロフィール入力へ遷移する
     */
    public function storeCompany(RegisterRequest $request)
    {
        // 入力をバリデーションする（RegisterRequest に委譲）
        $validated = $request->validated();

        // ユーザーを作成する（role=company）
        $user = User::create([
            // メールアドレスを保存する
            'email' => $validated['email'],
            // パスワードをハッシュ化して保存する
            'password' => Hash::make($validated['password']),
            // 役割を企業にする
            'role' => 'company',
        ]);

        // 作成したユーザーでログインさせる
        Auth::login($user);

        // 企業プロフィール登録画面へ遷移する（/company/profile 相当）
        return redirect('/company/profile');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        // Laravelのログアウト処理を呼ぶ
        Auth::logout();

        // セッションを無効化して安全にする
        $request->session()->invalidate();

        // CSRFトークンも再生成する
        $request->session()->regenerateToken();

        // ログイン画面へ戻す
        return redirect('/login');
    }
}