<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreelancerProfileController;
use App\Http\Controllers\FreelancerJobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FreelancerApplicationController;
use App\Http\Controllers\FreelancerMessageController;
use App\Http\Controllers\FreelancerScoutController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CompanyFreelancerController;
use App\Http\Controllers\CompanyJobController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\CompanyApplicationController;
use App\Http\Controllers\CompanyMessageController;

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

// ログイン処理
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// ログアウト（クリック導線用：POST）
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// フリーランス 新規登録 表示（ログイン情報登録）
Route::get('/register/freelancer', [AuthController::class, 'showFreelancerRegister'])->name('auth.register.freelancer.form');

// フリーランス 新規登録 保存（ログイン情報登録）
Route::post('/register/freelancer', [AuthController::class, 'storeFreelancer'])->name('auth.register.freelancer.store');

// 企業 新規登録 表示（ログイン情報登録）
Route::get('/register/company', [AuthController::class, 'showCompanyRegister'])->name('auth.register.company.form');

// 企業 新規登録 保存（ログイン情報登録）
Route::post('/register/company', [AuthController::class, 'storeCompany'])->name('auth.register.company.store');

Route::middleware(['auth', 'freelancer'])->group(function () {
    // フリーランス プロフィール 表示
    Route::get('/freelancer/profile', [FreelancerProfileController::class, 'create'])->name('freelancer.profile.create');

    // フリーランス プロフィール 保存・更新
    Route::post('/freelancer/profile', [FreelancerProfileController::class, 'store'])->name('freelancer.profile.store');

    // フリーランス プロフィール設定 表示
    Route::get('/freelancer/profile/settings', [FreelancerProfileController::class, 'edit'])->name('freelancer.profile.settings');

    // フリーランス プロフィール設定 保存・更新
    Route::post('/freelancer/profile/settings', [FreelancerProfileController::class, 'update'])->name('freelancer.profile.settings.update');

    // 案件一覧
    Route::get('/freelancer/jobs', [FreelancerJobController::class, 'index'])->name('freelancer.jobs.index');

    // 案件詳細
    Route::get('/freelancer/jobs/{job}', [FreelancerJobController::class, 'show'])->name('freelancer.jobs.show');

    // 応募入力画面
    Route::get('/freelancer/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('freelancer.jobs.apply.create');

    // 応募処理
    Route::post('/freelancer/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('freelancer.jobs.apply.store');

    // 応募一覧
    Route::get('/freelancer/applications', [FreelancerApplicationController::class, 'index'])->name('freelancer.applications.index');

    // チャット画面(応募・スカウト)
    Route::get('/freelancer/threads/{thread}', [FreelancerMessageController::class, 'show'])->name('freelancer.threads.show');

    // メッセージ送信
    Route::post('/freelancer/threads/{thread}/messages', [FreelancerMessageController::class, 'store'])->name('freelancer.threads.messages.store');

    // メッセージ削除
    Route::delete('/freelancer/messages/{message}', [FreelancerMessageController::class, 'destroy'])->name('freelancer.messages.destroy');

    // スカウト一覧
    Route::get('/freelancer/scouts', [FreelancerScoutController::class, 'index'])->name('freelancer.scouts.index');
});

Route::middleware(['auth', 'company'])->group(function () {
    // 企業 プロフィール 表示
    Route::get('/company/profile', [CompanyProfileController::class, 'create'])->name('company.profile.create');

    // 企業 プロフィール 保存・更新
    Route::post('/company/profile', [CompanyProfileController::class, 'store'])->name('company.profile.store');

    // 企業 プロフィール設定 表示
    Route::get('/company/profile/settings', [CompanyProfileController::class, 'edit'])->name('company.profile.settings');

    // 企業 プロフィール設定 保存・更新
    Route::post('/company/profile/settings', [CompanyProfileController::class, 'update'])->name('company.profile.settings.update');

    // フリーランス一覧
    Route::get('/company/freelancers', [CompanyFreelancerController::class, 'index'])->name('company.freelancers.index');

    // フリーランス詳細
    Route::get('/company/freelancers/{freelancer}', [CompanyFreelancerController::class, 'show'])->name('company.freelancers.show');

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

    // チャット画面(応募・スカウト)
    Route::get('/company/threads/{thread}', [CompanyMessageController::class, 'show'])->name('company.threads.show');

    // メッセージ送信
    Route::post('/company/threads/{thread}/messages', [CompanyMessageController::class, 'store'])->name('company.threads.messages.store');

    // メッセージ削除
    Route::delete('/company/messages/{message}', [CompanyMessageController::class, 'destroy'])->name('company.messages.destroy');
});