<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerCustomSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_custom_skills', function (Blueprint $table) {
            // ID
            $table->id();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 閾ｪ逕ｱ蜈･蜉帙せ繧ｭ繝ｫ蜷搾ｼ域､懃ｴ｢蟇ｾ雎｡・・            $table->string('name');

            // 陦ｨ遉ｺ鬆・ｺ・            $table->unsignedSmallInteger('sort_order')->default(0);

            // 剥 讀懃ｴ｢逕ｨ繧､繝ｳ繝・ャ繧ｯ繧ｹ
            $table->index('name');
            
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
        Schema::dropIfExists('freelancer_custom_skills');
    }
}