<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancers', function (Blueprint $table) {
            // ID
            $table->id();

            // 繝ｦ繝ｼ繧ｶ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 陦ｨ遉ｺ蜷・            $table->string('display_name');

            // 閨ｷ遞ｮ
            $table->string('job_title')->nullable();

            // 閾ｪ蟾ｱ邏ｹ莉・            $table->text('bio');

            // ==========================
            // 遞ｼ蜒肴擅莉ｶ・域ｭ｣隕丞喧・・            // ==========================
            // 騾ｱ縺ｮ遞ｼ蜒肴凾髢難ｼ井ｸ矩剞・・            $table->unsignedTinyInteger('min_hours_per_week');

            // 騾ｱ縺ｮ遞ｼ蜒肴凾髢難ｼ井ｸ企剞・・            $table->unsignedTinyInteger('max_hours_per_week');

            // 1譌･縺ｮ遞ｼ蜒肴凾髢・            $table->unsignedTinyInteger('hours_per_day');

            // 騾ｱ縺ｮ遞ｼ蜒肴律謨ｰ
            $table->unsignedTinyInteger('days_per_week');

            // ==========================
            // 蜒阪″譁ｹ・郁・逕ｱ蜈･蜉幢ｼ・            // ==========================
            // 蜒阪″譁ｹ縺ｮ隱ｬ譏・            $table->text('work_style_text')->nullable();

            // 蟶梧悍蜊倅ｾ｡・井ｸ矩剞・・            $table->unsignedInteger('min_rate');
            
            // 蟶梧悍蜊倅ｾ｡・井ｸ企剞・・            $table->unsignedInteger('max_rate');

            // 邨碁ｨ謎ｼ∵･ｭ
            $table->text('experience_companies')->nullable();

            // 繧｢繧､繧ｳ繝ｳ逕ｻ蜒上ヱ繧ｹ
            $table->string('icon_path')->nullable();
            
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
        Schema::dropIfExists('freelancers');
    }
}