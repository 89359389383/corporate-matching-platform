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

            // フリーランサーID（外部キー）
            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 自由入力スキル名（検索対象）
            $table->string('name');

            // 表示順
            $table->unsignedSmallInteger('sort_order')->default(0);

            // 🔍 検索用インデックス
            $table->index('name');
            
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
        Schema::dropIfExists('freelancer_custom_skills');
    }
}