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

            // 莨∵･ｭID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // 譯井ｻｶ蜷・            $table->string('title');

            // 譯井ｻｶ讎りｦ・            $table->text('description');

            // 蠢・ｦ√せ繧ｭ繝ｫ・医ユ繧ｭ繧ｹ繝茨ｼ・            $table->text('required_skills_text')->nullable();

            // ==========================
            // 蝣ｱ驟ｬ險ｭ螳・            // ==========================
            // 蝣ｱ驟ｬ繧ｿ繧､繝暦ｼ・onthly: 譛磯｡・譯井ｻｶ蜊倅ｾ｡縲”ourly: 譎らｵｦ・・            $table->enum('reward_type', ['monthly', 'hourly']);

            // 譛菴主腰萓｡
            $table->unsignedInteger('min_rate');
            
            // 譛鬮伜腰萓｡
            $table->unsignedInteger('max_rate');

            // 遞ｼ蜒肴擅莉ｶ・郁｡ｨ遉ｺ逕ｨ・・            $table->string('work_time_text');

            // 繧ｹ繝・・繧ｿ繧ｹ・・:荳区嶌縺・/ 1:蜈ｬ髢倶ｸｭ / 2:蛛懈ｭ｢荳ｭ・・            $table->unsignedTinyInteger('status')->default(0);
            
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
        Schema::dropIfExists('jobs');
    }
}