<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_signatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->enum('signer_type', ['company', 'corporate']);
            $table->unsignedBigInteger('signer_id');

            $table->enum('organization_type', ['company', 'corporate']);
            $table->unsignedBigInteger('organization_id');

            $table->string('ip', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('signed_at');

            // 署名時点の文面ハッシュ（締結後に固定化される）
            $table->string('document_hash', 64);

            $table->timestamps();

            $table->unique(['contract_id', 'signer_type']);
            $table->index(['organization_type', 'organization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_signatures');
    }
};

