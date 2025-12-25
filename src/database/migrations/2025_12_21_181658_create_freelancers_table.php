<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancers', function (Blueprint $table) {
            // ID
            $table->id();

            // ユーザーID（外部キー）
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 表示名
            $table->string('display_name');

            // 職種
            $table->string('job_title')->nullable();

            // 自己紹介
            $table->text('bio');

            // ==========================
            // 稼働条件（正規化）
            // ==========================
            // 週の稼働時間（下限）
            $table->unsignedTinyInteger('min_hours_per_week');

            // 週の稼働時間（上限）
            $table->unsignedTinyInteger('max_hours_per_week');

            // 1日の稼働時間
            $table->unsignedTinyInteger('hours_per_day');

            // 週の稼働日数
            $table->unsignedTinyInteger('days_per_week');

            // ==========================
            // 働き方（自由入力）
            // ==========================
            // 働き方の説明
            $table->text('work_style_text')->nullable();

            // 希望単価（下限）
            $table->unsignedInteger('min_rate');
            
            // 希望単価（上限）
            $table->unsignedInteger('max_rate');

            // 経験企業
            $table->text('experience_companies')->nullable();

            // アイコン画像パス
            $table->string('icon_path')->nullable();
            
            // 作成日時・更新日時
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelancers');
    }
}