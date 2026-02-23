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
            
            // ユーザーID（外部キー）
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 企業名
            $table->string('name');

            // 企業名（カナ）
            $table->string('name_kana')->nullable();

            // 会社概要
            $table->text('overview')->nullable();

            // 連絡先名
            $table->string('contact_name')->nullable();
            
            // 部署名
            $table->string('department')->nullable();

            // 自己紹介
            $table->text('introduction')->nullable();

            // 連絡用メールアドレス
            $table->string('email')->nullable();

            // 法人番号（13桁）
            $table->string('corporate_number', 13)->nullable();

            // 代表電話番号
            $table->string('representative_phone', 32)->nullable();

            // 本社住所
            $table->string('hq_postal_code', 16)->nullable();
            $table->string('hq_prefecture', 64)->nullable();
            $table->string('hq_city', 128)->nullable();
            $table->string('hq_address', 255)->nullable();

            // 代表者情報
            $table->string('representative_last_name', 20)->nullable();
            $table->string('representative_first_name', 20)->nullable();
            $table->string('representative_last_name_kana', 20)->nullable();
            $table->string('representative_first_name_kana', 20)->nullable();
            $table->date('representative_birthdate')->nullable();
            
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
        Schema::dropIfExists('companies');
    }
}