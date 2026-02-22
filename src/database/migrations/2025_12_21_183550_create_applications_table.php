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