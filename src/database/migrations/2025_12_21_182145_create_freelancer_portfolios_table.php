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

            // フリーランサーID（外部キー）
            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // ポートフォリオURL
            $table->string('url');
            
            // 表示順
            $table->unsignedSmallInteger('sort_order')->default(0);
            
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
        Schema::dropIfExists('freelancer_portfolios');
    }
}