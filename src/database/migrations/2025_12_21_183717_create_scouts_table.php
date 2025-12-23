<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scouts', function (Blueprint $table) {
            // ID
            $table->id();

            // 莨∵･ｭID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 譯井ｻｶID・亥､夜Κ繧ｭ繝ｼ縲∽ｻｻ諢擾ｼ・            $table->foreignId('job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            // 繧ｹ繧ｫ繧ｦ繝医Γ繝・そ繝ｼ繧ｸ
            $table->text('message');

            // 繧ｹ繝・・繧ｿ繧ｹ・・:譛ｪ蟇ｾ蠢・/ 1:蟇ｾ蠢應ｸｭ / 2:繧ｯ繝ｭ繝ｼ繧ｺ・・            $table->unsignedTinyInteger('status')->default(0);

            // 繧､繝ｳ繝・ャ繧ｯ繧ｹ・井ｼ∵･ｭ縲√ヵ繝ｪ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ縲∵｡井ｻｶ縺ｮ邨・∩蜷医ｏ縺幢ｼ・            $table->index(['company_id', 'freelancer_id', 'job_id']);
            
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
        Schema::dropIfExists('scouts');
    }
}