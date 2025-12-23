<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_portfolios', function (Blueprint $table) {
            // ID
            $table->id();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 繝昴・繝医ヵ繧ｩ繝ｪ繧ｪURL
            $table->string('url');
            
            // 陦ｨ遉ｺ鬆・ｺ・            $table->unsignedSmallInteger('sort_order')->default(0);
            
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
        Schema::dropIfExists('freelancer_portfolios');
    }
}