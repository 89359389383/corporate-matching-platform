<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('name_kana')->nullable()->after('name');

            $table->string('email')->nullable()->after('introduction');
            $table->string('corporate_number', 13)->nullable()->after('email');
            $table->string('representative_phone', 32)->nullable()->after('corporate_number');

            $table->string('hq_postal_code', 16)->nullable()->after('representative_phone');
            $table->string('hq_prefecture', 64)->nullable()->after('hq_postal_code');
            $table->string('hq_city', 128)->nullable()->after('hq_prefecture');
            $table->string('hq_address', 255)->nullable()->after('hq_city');

            $table->string('representative_last_name', 20)->nullable()->after('hq_address');
            $table->string('representative_first_name', 20)->nullable()->after('representative_last_name');
            $table->string('representative_last_name_kana', 20)->nullable()->after('representative_first_name');
            $table->string('representative_first_name_kana', 20)->nullable()->after('representative_last_name_kana');
            $table->date('representative_birthdate')->nullable()->after('representative_first_name_kana');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'name_kana',
                'email',
                'corporate_number',
                'representative_phone',
                'hq_postal_code',
                'hq_prefecture',
                'hq_city',
                'hq_address',
                'representative_last_name',
                'representative_first_name',
                'representative_last_name_kana',
                'representative_first_name_kana',
                'representative_birthdate',
            ]);
        });
    }
};

