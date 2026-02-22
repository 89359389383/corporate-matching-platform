<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
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

            // 最新送信者のタイプ（企業/法人）
            $table->enum('latest_sender_type', ['company', 'corporate'])->nullable();
            
            // 最新送信者のID
            $table->unsignedBigInteger('latest_sender_id')->nullable();

            // 最新メッセージの送信日時
            $table->timestamp('latest_message_at')->nullable();

            // 企業側の未読フラグ
            $table->boolean('is_unread_for_company')->default(false);
            
            // 法人側の未読フラグ
            $table->boolean('is_unread_for_corporate')->default(false);

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
        Schema::dropIfExists('threads');
    }
}