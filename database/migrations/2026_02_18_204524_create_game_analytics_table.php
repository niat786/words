<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();
            $table->string('game_key');
            $table->string('client_event_id');
            $table->string('event_type');
            $table->string('status')->nullable();
            $table->unsignedSmallInteger('attempts')->nullable();
            $table->unsignedSmallInteger('word_length')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['game_key', 'created_at']);
            $table->index(['event_type', 'created_at']);
            $table->unique(['user_id', 'client_event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_analytics');
    }
};
