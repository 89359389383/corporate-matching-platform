<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('thread_id')
                ->constrained('threads')
                ->cascadeOnDelete();

            // 検索・認可を簡単にするため冗長保持（threadからも導出可能）
            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('corporate_id')
                ->constrained('corporates')
                ->cascadeOnDelete();

            $table->foreignId('job_id')
                ->nullable()
                ->constrained('jobs')
                ->nullOnDelete();

            $table->string('contract_type', 32); // NDA / basic / individual
            $table->unsignedInteger('version'); // 1,2,3...

            $table->string('status', 32); // draft, proposed, negotiating, ready_to_sign, signed, active, completed, terminated, archived

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // 取引条件等（本文相当、自由記述含む）
            $table->json('terms_json');

            // 状態に応じた時刻
            $table->timestamp('proposed_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            // 新版が作られたら旧版にセット（currentはnull）
            $table->unsignedBigInteger('superseded_by_contract_id')->nullable();

            // 締結後のみ
            $table->string('pdf_path')->nullable();
            $table->string('document_hash', 64)->nullable();
            $table->string('pdf_hash', 64)->nullable();

            $table->timestamps();

            $table->unique(['thread_id', 'version']);
            $table->index(['thread_id', 'status']);
            $table->index(['company_id', 'status']);
            $table->index(['corporate_id', 'status']);
            $table->index(['superseded_by_contract_id']);
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('superseded_by_contract_id')
                ->references('id')
                ->on('contracts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['superseded_by_contract_id']);
        });

        Schema::dropIfExists('contracts');
    }
};

