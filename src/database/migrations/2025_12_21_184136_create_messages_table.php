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

            // 繧ｹ繝ｬ繝・ラID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('thread_id')
                ->constrained('threads')
                ->cascadeOnDelete();

            // 騾∽ｿ｡閠・・繧ｿ繧､繝暦ｼ井ｼ∵･ｭ/繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ・・            $table->enum('sender_type', ['company', 'freelancer']);
            
            // 騾∽ｿ｡閠・・ID
            $table->unsignedBigInteger('sender_id');

            // 繝｡繝・そ繝ｼ繧ｸ譛ｬ譁・            $table->text('body');
            
            // 騾∽ｿ｡譌･譎・            $table->timestamp('sent_at');

            // 繧ｽ繝輔ヨ繝・Μ繝ｼ繝茨ｼ郁・蛻・・繝｡繝・そ繝ｼ繧ｸ蜑企勁蟇ｾ蠢懶ｼ・            $table->softDeletes();

            // 繧､繝ｳ繝・ャ繧ｯ繧ｹ・医せ繝ｬ繝・ラID縺ｨ騾∽ｿ｡譌･譎ゅ・邨・∩蜷医ｏ縺幢ｼ・            $table->index(['thread_id', 'sent_at']);
            
            // 菴懈・譌･譎ゅ・譖ｴ譁ｰ譌･譎・            $table->timestamps();
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