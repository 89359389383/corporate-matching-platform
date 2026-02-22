<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scouts', function (Blueprint $table) {
            // ID
            $table->id();

            // 企業ID（外部キー）
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // 法人ID（外部キー）
            $table->foreignId('corporate_id')
                ->constrained('corporates')
                ->cascadeOnDelete();

            // 案件ID（外部キー、任意）
            $table->foreignId('job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            // スカウトメッセージ
            $table->text('message');

            // ステータス：0:未対応 / 1:対応中 / 2:クローズ
            $table->unsignedTinyInteger('status')->default(0);

            // インデックス：企業、法人、案件の組み合わせ
            $table->index(['company_id', 'corporate_id', 'job_id']);
            
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
        Schema::dropIfExists('scouts');
    }
}