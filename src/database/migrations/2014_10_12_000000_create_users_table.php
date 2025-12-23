<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // ID
            $table->id();
            
            // 繝｡繝ｼ繝ｫ繧｢繝峨Ξ繧ｹ
            $table->string('email')->unique();
            
            // 繝｡繝ｼ繝ｫ隱崎ｨｼ譌･譎・            $table->timestamp('email_verified_at')->nullable();
            
            // 繝代せ繝ｯ繝ｼ繝・            $table->string('password');
            
            // 繝ｭ繝ｼ繝ｫ・井ｼ∵･ｭ/繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ・・            $table->enum('role', ['company', 'freelancer']);
            
            // 繝ｭ繧ｰ繧､繝ｳ險俶・逕ｨ繝医・繧ｯ繝ｳ
            $table->rememberToken();
            
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
        Schema::dropIfExists('users');
    }
}