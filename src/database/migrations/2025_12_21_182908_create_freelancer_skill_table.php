<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_skill', function (Blueprint $table) {
            // ID
            $table->id();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 繧ｹ繧ｭ繝ｫID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            // 繝ｦ繝九・繧ｯ蛻ｶ邏・ｼ医ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ縺ｨ繧ｹ繧ｭ繝ｫ縺ｮ邨・∩蜷医ｏ縺幢ｼ・            $table->unique(['freelancer_id', 'skill_id']);
            
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
        Schema::dropIfExists('freelancer_skill');
    }
}