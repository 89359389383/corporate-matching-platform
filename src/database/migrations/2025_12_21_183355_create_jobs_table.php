<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            // ID
            $table->id();

            // 企業ID（外部キー）
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // 案件名
            $table->string('title');

            // 案件概要
            $table->text('description');

            // 必須スキル（テキスト）
            $table->text('required_skills_text')->nullable();

            // ==========================
            // 報酬設定
            // ==========================
            // 報酬タイプ：monthly: 月額/案件単価、hourly: 時給
            $table->enum('reward_type', ['monthly', 'hourly']);

            // 最低単価
            $table->unsignedInteger('min_rate');
            
            // 最高単価
            $table->unsignedInteger('max_rate');

            // 稼働条件（表示用）
            $table->string('work_time_text');

            // ステータス：0:下書き / 1:公開中 / 2:停止中
            $table->unsignedTinyInteger('status')->default(0);
            
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
        Schema::dropIfExists('jobs');
    }
}