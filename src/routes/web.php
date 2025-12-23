<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/login', 'App\\Http\\Controllers\\AuthController@showLoginForm')->name('auth.login.form');

// ログイン処理
Route::post('/login', 'App\\Http\\Controllers\\AuthController@login')->name('auth.login');

// ログアウト（クリック導線用：POST）
Route::post('/logout', 'App\\Http\\Controllers\\AuthController@logout')->name('auth.logout');

// フリーランス 新規登録 表示（ログイン情報登録）
Route::get('/register/freelancer', 'App\\Http\\Controllers\\AuthController@showFreelancerRegister')->name('auth.register.freelancer.form');

// フリーランス 新規登録 保存（ログイン情報登録）
Route::post('/register/freelancer', 'App\\Http\\Controllers\\AuthController@storeFreelancer')->name('auth.register.freelancer.store');

// 企業 新規登録 表示（ログイン情報登録）
Route::get('/register/company', 'App\\Http\\Controllers\\AuthController@showCompanyRegister')->name('auth.register.company.form');

// 企業 新規登録 保存（ログイン情報登録）
Route::post('/register/company', 'App\\Http\\Controllers\\AuthController@storeCompany')->name('auth.register.company.store');

Route::middleware(['auth', 'freelancer'])->group(function () {
    // フリーランス プロフィール 表示
    Route::get('/freelancer/profile', 'App\\Http\\Controllers\\FreelancerProfileController@create')->name('freelancer.profile.create');

    // フリーランス プロフィール 保孁E更新
    Route::post('/freelancer/profile', 'App\\Http\\Controllers\\FreelancerProfileController@store')->name('freelancer.profile.store');

    // フリーランス プロフィール設宁E表示
    Route::get('/freelancer/profile/settings', 'App\\Http\\Controllers\\FreelancerProfileController@edit')->name('freelancer.profile.settings');

    // フリーランス プロフィール設宁E保孁E更新
    Route::post('/freelancer/profile/settings', 'App\\Http\\Controllers\\FreelancerProfileController@update')->name('freelancer.profile.settings.update');

    // 案件一覧
    Route::get('/freelancer/jobs', 'App\\Http\\Controllers\\FreelancerJobController@index')->name('freelancer.jobs.index');

    // 案件詳細
    Route::get('/freelancer/jobs/{job}', 'App\\Http\\Controllers\\FreelancerJobController@show')->name('freelancer.jobs.show');

    // 応募入力画面
    Route::get('/freelancer/jobs/{job}/apply', 'App\\Http\\Controllers\\ApplicationController@create')->name('freelancer.jobs.apply.create');

    // 応募処理
    Route::post('/freelancer/jobs/{job}/apply', 'App\\Http\\Controllers\\ApplicationController@store')->name('freelancer.jobs.apply.store');

    // 応募一覧
    Route::get('/freelancer/applications', 'App\\Http\\Controllers\\FreelancerApplicationController@index')->name('freelancer.applications.index');

    // チャチE��画面(応募・スカウチE
    Route::get('/freelancer/threads/{thread}', 'App\\Http\\Controllers\\FreelancerMessageController@show')->name('freelancer.threads.show');

    // メチE��ージ送信
    Route::post('/freelancer/threads/{thread}/messages', 'App\\Http\\Controllers\\FreelancerMessageController@store')->name('freelancer.threads.messages.store');

    // メチE��ージ削除
    Route::delete('/freelancer/messages/{message}', 'App\\Http\\Controllers\\FreelancerMessageController@destroy')->name('freelancer.messages.destroy');

    // スカウト一覧
    Route::get('/freelancer/scouts', 'App\\Http\\Controllers\\FreelancerScoutController@index')->name('freelancer.scouts.index');
});

Route::middleware(['auth', 'company'])->group(function () {
    // 企業 プロフィール 表示
    Route::get('/company/profile', 'App\\Http\\Controllers\\CompanyProfileController@create')->name('company.profile.create');

    // 企業 プロフィール 保孁E更新
    Route::post('/company/profile', 'App\\Http\\Controllers\\CompanyProfileController@store')->name('company.profile.store');

    // 企業 プロフィール設宁E表示
    Route::get('/company/profile/settings', 'App\\Http\\Controllers\\CompanyProfileController@edit')->name('company.profile.settings');

    // 企業 プロフィール設宁E保孁E更新
    Route::post('/company/profile/settings', 'App\\Http\\Controllers\\CompanyProfileController@update')->name('company.profile.settings.update');

    // フリーランス一覧
    Route::get('/company/freelancers', 'App\\Http\\Controllers\\CompanyFreelancerController@index')->name('company.freelancers.index');

    // フリーランス詳細
    Route::get('/company/freelancers/{freelancer}', 'App\\Http\\Controllers\\CompanyFreelancerController@show')->name('company.freelancers.show');

    // 案件一覧
    Route::get('/company/jobs', 'App\\Http\\Controllers\\CompanyJobController@index')->name('company.jobs.index');

    // 案件 新規登録 表示
    Route::get('/company/jobs/create', 'App\\Http\\Controllers\\CompanyJobController@create')->name('company.jobs.create');

    // 案件 新規登録 保存
    Route::post('/company/jobs', 'App\\Http\\Controllers\\CompanyJobController@store')->name('company.jobs.store');

    // 案件 編雁E表示
    Route::get('/company/jobs/{job}/edit', 'App\\Http\\Controllers\\CompanyJobController@edit')->name('company.jobs.edit');

    // 案件 更新
    Route::match(['put', 'patch'], '/company/jobs/{job}', 'App\\Http\\Controllers\\CompanyJobController@update')->name('company.jobs.update');

    // 案件削除
    Route::delete('/company/jobs/{job}', 'App\\Http\\Controllers\\CompanyJobController@destroy')->name('company.jobs.destroy');

    // スカウチE送信 表示
    Route::get('/company/scouts/create', 'App\\Http\\Controllers\\ScoutController@create')->name('company.scouts.create');

    // スカウト送信 処理
    Route::post('/company/scouts', 'App\\Http\\Controllers\\ScoutController@store')->name('company.scouts.store');

    // スカウト一覧
    Route::get('/company/scouts', 'App\\Http\\Controllers\\ScoutController@index')->name('company.scouts.index');

    // 応募一覧
    Route::get('/company/applications', 'App\\Http\\Controllers\\CompanyApplicationController@index')->name('company.applications.index');

    // チャチE��画面(応募・スカウチE
    Route::get('/company/threads/{thread}', 'App\\Http\\Controllers\\CompanyMessageController@show')->name('company.threads.show');

    // メチE��ージ送信
    Route::post('/company/threads/{thread}/messages', 'App\\Http\\Controllers\\CompanyMessageController@store')->name('company.threads.messages.store');

    // メチE��ージ削除
    Route::delete('/company/messages/{message}', 'App\\Http\\Controllers\\CompanyMessageController@destroy')->name('company.messages.destroy');
});