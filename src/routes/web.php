<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorporateProfileController;
use App\Http\Controllers\CorporateJobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CorporateApplicationController;
use App\Http\Controllers\CorporateMessageController;
use App\Http\Controllers\CorporateScoutController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CompanyCorporateController;
use App\Http\Controllers\CompanyJobController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\CompanyApplicationController;
use App\Http\Controllers\CompanyMessageController;
use App\Http\Controllers\CompanyContractController;
use App\Http\Controllers\CorporateContractController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| フリーランスマッチングプラチE��フォーム�E�ルーチE��ング�E�機�Eフロー対応！E|--------------------------------------------------------------------------
| 目皁E��どのURLが「どのController@method」に対応するかを�EかりめE��くすめE| 注意：コントローラー未実裁E��もルーチE��ング定義でアプリが落ちなぁE��ぁE��文字�E持E��で書ぁE*/

// ログイン画面表示
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');

// ログイン処理（guard別）
Route::post('/login/corporate', [AuthController::class, 'loginCorporate'])->name('auth.login.corporate');
Route::post('/login/company', [AuthController::class, 'loginCompany'])->name('auth.login.company');

// ログアウト（クリック導線用：POST）
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// パスワード再設定メール送信フォーム（メールアドレス入力画面）
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

// パスワード再設定メール送信処理（リセットリンクメールを送る）
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

// パスワード再設定ページ表示（メール内リンクからアクセスする画面）
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

// パスワード更新処理（新しいパスワードを保存する）
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

// 法人 新規登録 表示（ログイン情報登録）
Route::get('/register/corporate', [AuthController::class, 'showCorporateRegister'])->name('auth.register.corporate.form');

// 法人 新規登録 保存（ログイン情報登録）
Route::post('/register/corporate', [AuthController::class, 'storeCorporate'])->name('auth.register.corporate.store');

// 企業 新規登録 表示（ログイン情報登録）
Route::get('/register/company', [AuthController::class, 'showCompanyRegister'])->name('auth.register.company.form');

// 企業 新規登録 保存（ログイン情報登録）
Route::post('/register/company', [AuthController::class, 'storeCompany'])->name('auth.register.company.store');

Route::middleware(['auth:corporate', 'corporate'])->group(function () {
    // 法人 プロフィール 表示
    Route::get('/corporate/profile', [CorporateProfileController::class, 'create'])->name('corporate.profile.create');

    // 法人 プロフィール 保存・更新
    Route::post('/corporate/profile', [CorporateProfileController::class, 'store'])->name('corporate.profile.store');

    // 法人 プロフィール設定 表示
    Route::get('/corporate/profile/settings', [CorporateProfileController::class, 'edit'])->name('corporate.profile.settings');

    // 法人 プロフィール設定 保存・更新
    Route::post('/corporate/profile/settings', [CorporateProfileController::class, 'update'])->name('corporate.profile.settings.update');

    // 案件一覧
    Route::get('/corporate/jobs', [CorporateJobController::class, 'index'])->name('corporate.jobs.index');

    // 案件詳細
    Route::get('/corporate/jobs/{job}', [CorporateJobController::class, 'show'])->name('corporate.jobs.show');

    // 応募入力画面
    Route::get('/corporate/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('corporate.jobs.apply.create');

    // 応募処理
    Route::post('/corporate/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('corporate.jobs.apply.store');

    // 応募一覧
    Route::get('/corporate/applications', [CorporateApplicationController::class, 'index'])->name('corporate.applications.index');

    // チャット画面(応募・スカウト)
    Route::get('/corporate/threads/{thread}', [CorporateMessageController::class, 'show'])->name('corporate.threads.show');

    // メッセージ送信
    Route::post('/corporate/threads/{thread}/messages', [CorporateMessageController::class, 'store'])->name('corporate.threads.messages.store');

    // メッセージ削除
    Route::delete('/corporate/messages/{message}', [CorporateMessageController::class, 'destroy'])->name('corporate.messages.destroy');

    // スカウト一覧
    Route::get('/corporate/scouts', [CorporateScoutController::class, 'index'])->name('corporate.scouts.index');

    // 契約（法人）
    Route::get('/corporate/contracts', [CorporateContractController::class, 'index'])->name('corporate.contracts.index');
    Route::get('/corporate/threads/{thread}/contracts', [CorporateContractController::class, 'threadIndex'])->name('corporate.threads.contracts.index');
    Route::get('/corporate/contracts/{contract}', [CorporateContractController::class, 'show'])->name('corporate.contracts.show');
    Route::post('/corporate/contracts/{contract}/return', [CorporateContractController::class, 'return'])->name('corporate.contracts.return');
    Route::post('/corporate/contracts/{contract}/agree', [CorporateContractController::class, 'agree'])->name('corporate.contracts.agree');
    Route::get('/corporate/contracts/{contract}/pdf', [CorporateContractController::class, 'pdf'])->name('corporate.contracts.pdf');
});

Route::middleware(['auth:company', 'company'])->group(function () {
    // 企業 プロフィール 表示
    Route::get('/company/profile', [CompanyProfileController::class, 'create'])->name('company.profile.create');

    // 企業 プロフィール 保存・更新
    Route::post('/company/profile', [CompanyProfileController::class, 'store'])->name('company.profile.store');

    // 企業 プロフィール設定 表示
    Route::get('/company/profile/settings', [CompanyProfileController::class, 'edit'])->name('company.profile.settings');

    // 企業 プロフィール設定 保存・更新
    Route::post('/company/profile/settings', [CompanyProfileController::class, 'update'])->name('company.profile.settings.update');

    // 法人一覧
    Route::get('/company/corporates', [CompanyCorporateController::class, 'index'])->name('company.corporates.index');

    // 法人詳細
    Route::get('/company/corporates/{corporate}', [CompanyCorporateController::class, 'show'])->name('company.corporates.show');

    // 案件一覧
    Route::get('/company/jobs', [CompanyJobController::class, 'index'])->name('company.jobs.index');

    // 案件 新規登録 表示
    Route::get('/company/jobs/create', [CompanyJobController::class, 'create'])->name('company.jobs.create');

    // 案件 新規登録 保存
    Route::post('/company/jobs', [CompanyJobController::class, 'store'])->name('company.jobs.store');

    // 案件 編集 表示
    Route::get('/company/jobs/{job}/edit', [CompanyJobController::class, 'edit'])->name('company.jobs.edit');

    // 案件 更新
    Route::match(['put', 'patch'], '/company/jobs/{job}', [CompanyJobController::class, 'update'])->name('company.jobs.update');

    // 案件ステータス更新
    Route::patch('/company/jobs/{job}/status', [CompanyJobController::class, 'updateStatus'])->name('company.jobs.status.update');

    // 案件削除
    Route::delete('/company/jobs/{job}', [CompanyJobController::class, 'destroy'])->name('company.jobs.destroy');

    // スカウト送信 表示
    Route::get('/company/scouts/create', [ScoutController::class, 'create'])->name('company.scouts.create');

    // スカウト送信 処理
    Route::post('/company/scouts', [ScoutController::class, 'store'])->name('company.scouts.store');

    // スカウト一覧
    Route::get('/company/scouts', [ScoutController::class, 'index'])->name('company.scouts.index');

    // 応募一覧
    Route::get('/company/applications', [CompanyApplicationController::class, 'index'])->name('company.applications.index');

    // 応募ステータス更新
    Route::patch('/company/applications/{application}', [CompanyApplicationController::class, 'update'])->name('company.applications.update');

    // チャット画面(応募・スカウト)
    Route::get('/company/threads/{thread}', [CompanyMessageController::class, 'show'])->name('company.threads.show');

    // メッセージ送信
    Route::post('/company/threads/{thread}/messages', [CompanyMessageController::class, 'store'])->name('company.threads.messages.store');

    // メッセージ削除
    Route::delete('/company/messages/{message}', [CompanyMessageController::class, 'destroy'])->name('company.messages.destroy');

    // 応募ステータス更新
    Route::patch('/company/threads/{thread}/application-status', [CompanyMessageController::class, 'updateApplicationStatus'])->name('company.threads.application-status.update');

    // 契約（企業）
    Route::get('/company/contracts', [CompanyContractController::class, 'index'])->name('company.contracts.index');
    Route::get('/company/threads/{thread}/contracts', [CompanyContractController::class, 'threadIndex'])->name('company.threads.contracts.index');
    Route::get('/company/threads/{thread}/contracts/create', [CompanyContractController::class, 'create'])->name('company.threads.contracts.create');
    Route::post('/company/threads/{thread}/contracts', [CompanyContractController::class, 'store'])->name('company.threads.contracts.store');
    Route::get('/company/contracts/{contract}', [CompanyContractController::class, 'show'])->name('company.contracts.show');
    Route::get('/company/contracts/{contract}/edit', [CompanyContractController::class, 'edit'])->name('company.contracts.edit');
    Route::patch('/company/contracts/{contract}', [CompanyContractController::class, 'update'])->name('company.contracts.update');
    Route::post('/company/contracts/{contract}/propose', [CompanyContractController::class, 'propose'])->name('company.contracts.propose');
    Route::get('/company/contracts/{contract}/versions/create', [CompanyContractController::class, 'createVersion'])->name('company.contracts.versions.create');
    Route::post('/company/contracts/{contract}/versions', [CompanyContractController::class, 'storeVersion'])->name('company.contracts.versions.store');
    Route::post('/company/contracts/{contract}/agree', [CompanyContractController::class, 'agree'])->name('company.contracts.agree');
    Route::post('/company/contracts/{contract}/complete', [CompanyContractController::class, 'complete'])->name('company.contracts.complete');
    Route::get('/company/contracts/{contract}/pdf', [CompanyContractController::class, 'pdf'])->name('company.contracts.pdf');
    Route::post('/company/contracts/{contract}/terminate', [CompanyContractController::class, 'terminate'])->name('company.contracts.terminate');
});