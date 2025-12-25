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

            // フリーランサーID（外部キー）
            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // スキルID（外部キー）
            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            // ユニーク制約：フリーランサーとスキルの組み合わせ
            $table->unique(['freelancer_id', 'skill_id']);
            
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
        Schema::dropIfExists('freelancer_skill');
    }
}