<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            // ID
            $table->id();

            // スレッドID（外部キー）
            $table->foreignId('thread_id')
                ->constrained('threads')
                ->cascadeOnDelete();

            // 送信者のタイプ（企業/フリーランサー）
            $table->enum('sender_type', ['company', 'corporate']);
            
            // 送信者のID
            $table->unsignedBigInteger('sender_id');

            // メッセージ本文
            $table->text('body');
            
            // 送信日時
            $table->timestamp('sent_at');

            // ソフトデリート（論理削除メッセージ削除対応）
            $table->softDeletes();

            // インデックス：スレッドIDと送信日時の組み合わせ
            $table->index(['thread_id', 'sent_at']);
            
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
        Schema::dropIfExists('messages');
    }
}