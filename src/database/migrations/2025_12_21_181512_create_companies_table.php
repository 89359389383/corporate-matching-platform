<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            // ID
            $table->id();
            
            // 繝ｦ繝ｼ繧ｶ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 莨∵･ｭ蜷・            $table->string('name');

            // 莨夂､ｾ讎りｦ・            $table->text('overview')->nullable();

            // 諡・ｽ楢・錐
            $table->string('contact_name')->nullable();
            
            // 驛ｨ鄂ｲ蜷・            $table->string('department')->nullable();

            // 閾ｪ蟾ｱ邏ｹ莉・            $table->text('introduction')->nullable();
            
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
        Schema::dropIfExists('companies');
    }
}