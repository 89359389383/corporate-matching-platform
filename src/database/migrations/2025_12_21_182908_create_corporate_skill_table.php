<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporateSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_skill', function (Blueprint $table) {
            // ID
            $table->id();

            // 法人ID（外部キー）
            $table->foreignId('corporate_id')
                ->constrained('corporates')
                ->cascadeOnDelete();

            // スキルID（外部キー）
            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            // ユニーク制約：法人とスキルの組み合わせ
            $table->unique(['corporate_id', 'skill_id']);
            
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
        Schema::dropIfExists('corporate_skill');
    }
}