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

            // 莨∵･ｭID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 譯井ｻｶID・亥､夜Κ繧ｭ繝ｼ縲∽ｻｻ諢擾ｼ・            $table->foreignId('job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            // 譛譁ｰ騾∽ｿ｡閠・・繧ｿ繧､繝暦ｼ井ｼ∵･ｭ/繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ・・            $table->enum('latest_sender_type', ['company', 'freelancer'])->nullable();
            
            // 譛譁ｰ騾∽ｿ｡閠・・ID
            $table->unsignedBigInteger('latest_sender_id')->nullable();

            // 譛譁ｰ繝｡繝・そ繝ｼ繧ｸ縺ｮ騾∽ｿ｡譌･譎・            $table->timestamp('latest_message_at')->nullable();

            // 莨∵･ｭ蛛ｴ縺ｮ譛ｪ隱ｭ繝輔Λ繧ｰ
            $table->boolean('is_unread_for_company')->default(false);
            
            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ蛛ｴ縺ｮ譛ｪ隱ｭ繝輔Λ繧ｰ
            $table->boolean('is_unread_for_freelancer')->default(false);

            // 繧､繝ｳ繝・ャ繧ｯ繧ｹ・井ｼ∵･ｭ縲√ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ縲∵｡井ｻｶ縺ｮ邨・∩蜷医ｏ縺幢ｼ・            $table->index(['company_id', 'freelancer_id', 'job_id']);
            
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
        Schema::dropIfExists('threads');
    }
}