<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->string('action', 64);

            $table->enum('actor_type', ['company', 'corporate', 'system']);
            $table->unsignedBigInteger('actor_id')->nullable();

            $table->string('ip', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta_json')->nullable();

            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['contract_id', 'occurred_at']);
            $table->index(['action', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_audit_logs');
    }
};

