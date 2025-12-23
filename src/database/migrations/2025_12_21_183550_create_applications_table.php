<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            // ID
            $table->id();

            // 譯井ｻｶID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('job_id')
                ->constrained('jobs')
                ->cascadeOnDelete();

            // 繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼID・亥､夜Κ繧ｭ繝ｼ・・            $table->foreignId('freelancer_id')
                ->constrained('freelancers')
                ->cascadeOnDelete();

            // 蠢懷供繝｡繝・そ繝ｼ繧ｸ
            $table->text('message');

            // 繧ｹ繝・・繧ｿ繧ｹ・・:譛ｪ蟇ｾ蠢・/ 1:蟇ｾ蠢應ｸｭ / 2:繧ｯ繝ｭ繝ｼ繧ｺ・・            $table->unsignedTinyInteger('status')->default(0);

            // 繝ｦ繝九・繧ｯ蛻ｶ邏・ｼ域｡井ｻｶ縺ｨ繝輔Μ繝ｼ繝ｩ繝ｳ繧ｵ繝ｼ縺ｮ邨・∩蜷医ｏ縺幢ｼ・            $table->unique(['job_id', 'freelancer_id']);
            
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
        Schema::dropIfExists('applications');
    }
}