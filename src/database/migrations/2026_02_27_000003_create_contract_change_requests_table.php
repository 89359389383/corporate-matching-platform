<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_change_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->enum('requester_type', ['corporate']);
            $table->unsignedBigInteger('requester_id');

            $table->text('body');

            $table->timestamps();

            $table->index(['contract_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_change_requests');
    }
};

