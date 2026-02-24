<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            // ID
            $table->id();

            // 案件ID（外部キー）
            $table->foreignId('job_id')
                ->constrained('jobs')
                ->cascadeOnDelete();

            // 法人ID（外部キー）
            $table->foreignId('corporate_id')
                ->constrained('corporates')
                ->cascadeOnDelete();

            // 応募メッセージ
            $table->text('message');

            // 希望時間単価（円/時間）
            $table->unsignedInteger('desired_hourly_rate')->nullable();

            // 稼働曜日（例: ["月","火",... ]）
            $table->json('work_days')->nullable();

            // 稼働時間帯（目安）
            $table->string('work_time_from', 5)->nullable(); // HH:MM
            $table->string('work_time_to', 5)->nullable();   // HH:MM

            // 備考
            $table->text('note')->nullable();

            // 合計週稼働時間（目安）: 5 / 10 / 20 / 30 / 40
            $table->unsignedTinyInteger('weekly_hours')->nullable();

            // 開始可能日（即日 / 2週間後 / 1ヶ月後 / 3ヶ月後以降）
            $table->string('available_start', 20)->nullable();

            // ステータス：0:未対応 / 1:対応中 / 2:クローズ
            $table->unsignedTinyInteger('status')->default(0);

            // ユニーク制約：案件と法人の組み合わせ
            $table->unique(['job_id', 'corporate_id']);
            
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
        Schema::dropIfExists('applications');
    }
}