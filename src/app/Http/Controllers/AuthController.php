<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
     * フリーランスとしてログインする（freelancer guard）
     */
    public function loginFreelancer(LoginRequest $request)
    {
        // 入力を最低限チェックする（FormRequest に委譲）
        $credentials = $request->validated();

        // freelancer guard で認証を試みる
        if (!Auth::guard('freelancer')->attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスまたはパスワードが正しくありません']);
        }

        // セッション固定化攻撃を防ぐため、ログイン成功時にセッションIDを再生成します。
        // セッション固定化攻撃とは、攻撃者が事前に取得したセッションIDをユーザーに使用させ、
        // そのセッションIDでログインされた状態を乗っ取る攻撃手法です。
        // regenerate()を呼ぶことで、古いセッションIDは無効化され、新しいセッションIDが発行されます。
        // これにより、ログイン前のセッションIDではアクセスできなくなり、セキュリティが向上します。
        $request->session()->regenerate();

        // ログインしたユーザーを取得する
        /** @var User $user */
        $user = Auth::guard('freelancer')->user();

        // 想定外のroleなら安全側に倒してログアウトし、ログインへ戻す
        if (!$user || $user->role !== 'freelancer') {
            Auth::guard('freelancer')->logout();

            // セッションを無効化して安全にする
            $request->session()->invalidate();

            // CSRFトークンも再生成する
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors(['email' => 'フリーランスアカウントではありません']);
        }

        // フリーランスは案件一覧へ
        return redirect('/freelancer/jobs');
    }

    /**
     * 企業としてログインする（company guard）
     */
    public function loginCompany(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::guard('company')->attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスまたはパスワードが正しくありません']);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::guard('company')->user();

        if (!$user || $user->role !== 'company') {
            Auth::guard('company')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors(['email' => '企業アカウントではありません']);
        }

        // 企業はフリーランス一覧へ
        return redirect('/company/freelancers');
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
        Auth::guard('freelancer')->login($user);
        $request->session()->regenerate();

        // プロフィール登録画面へリダイレクトする（/freelancer/profile 相当）
        return redirect('/freelancer/profile');
    }

    /**
     * 企業登録画面を表示する（表示のみ）
     */
    public function showCompanyRegister(Request $request)
    {
        // メソッド開始ログ
        Log::info('showCompanyRegister: メソッド開始', [
            'method' => 'showCompanyRegister',
            'timestamp' => now()->toDateTimeString(),
        ]);

        // リクエスト情報のログ
        Log::info('showCompanyRegister: リクエスト情報', [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'referer' => $request->header('referer'),
            'query_params' => $request->query(),
            'request_headers' => $request->headers->all(),
        ]);

        // セッション情報のログ
        $sessionData = [];
        if ($request->hasSession()) {
            try {
                $sessionData = [
                    'session_id' => $request->session()->getId(),
                    'session_exists' => true,
                    'session_data_keys' => $request->session()->all() ? array_keys($request->session()->all()) : [],
                    'csrf_token' => $request->session()->token(),
                ];
            } catch (\Exception $e) {
                $sessionData = [
                    'session_exists' => true,
                    'error' => 'セッション情報の取得に失敗しました: ' . $e->getMessage(),
                ];
            }
        } else {
            $sessionData = [
                'session_exists' => false,
            ];
        }
        Log::info('showCompanyRegister: セッション情報', $sessionData);

        // 認証状態のログ
        $isAuthenticated = Auth::check();
        $user = Auth::user();
        Log::info('showCompanyRegister: 認証状態', [
            'is_authenticated' => $isAuthenticated,
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'user_role' => $user ? $user->role : null,
        ]);

        // ビュー返却前のログ
        Log::info('showCompanyRegister: ビュー返却前', [
            'view_name' => 'auth.register.company',
        ]);

        // 企業登録画面のBladeを返すだけ
        $view = view('auth.register.company');

        // ビュー返却後のログ
        Log::info('showCompanyRegister: メソッド終了', [
            'method' => 'showCompanyRegister',
            'timestamp' => now()->toDateTimeString(),
            'view_returned' => true,
        ]);

        return $view;
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
        Auth::guard('company')->login($user);
        $request->session()->regenerate();

        // 企業プロフィール登録画面へ遷移する（/company/profile 相当）
        return redirect('/company/profile');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        // どちらでログインしていても確実に落とす（同時ログインは要件外だが、安全側）
        Auth::guard('freelancer')->logout();
        Auth::guard('company')->logout();
        Auth::logout();

        // セッションを無効化して安全にする
        $request->session()->invalidate();

        // CSRFトークンも再生成する
        $request->session()->regenerateToken();

        // ログイン画面へ戻す
        return redirect('/login');
    }
}