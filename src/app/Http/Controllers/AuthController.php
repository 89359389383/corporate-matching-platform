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

        // セッション固定化攻撃を防ぐため、ログイン成功時にセッションIDを再生成します。
        // セッション固定化攻撃とは、攻撃者が事前に取得したセッションIDをユーザーに使用させ、
        // そのセッションIDでログインされた状態を乗っ取る攻撃手法です。
        // regenerate()を呼ぶことで、古いセッションIDは無効化され、新しいセッションIDが発行されます。
        // これにより、ログイン前のセッションIDではアクセスできなくなり、セキュリティが向上します。
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

        // 現在のセッションを完全に無効化して破棄します。
        // invalidate()を呼ぶことで、セッションに保存されている全てのデータが削除され、
        // セッションIDも無効になります。これにより、ログアウト処理の後に
        // セッションに残っている認証情報やその他のデータが誤って再利用されることを防ぎます。
        // 中途半端なログイン状態を残さないことで、セキュリティを確保します。
        $request->session()->invalidate();

        // CSRF（Cross-Site Request Forgery）トークンを再生成します。
        // CSRF攻撃とは、ユーザーが意図しないリクエストを外部サイトから送信させる攻撃です。
        // セッションを無効化した後は、古いCSRFトークンも無効になっているため、
        // 新しいセッション用に新しいCSRFトークンを生成する必要があります。
        // これにより、次のリクエストから正常にCSRF保護が機能するようになります。
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