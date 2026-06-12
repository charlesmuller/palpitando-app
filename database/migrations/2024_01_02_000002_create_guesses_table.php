<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users');
            $table->boolean('is_public')->default(false);
            $table->string('invite_code', 10)->unique()->nullable();
            $table->enum('status', ['open', 'closed', 'finished'])->default('open');
            $table->unsignedTinyInteger('points_exact_score')->default(10);
            $table->unsignedTinyInteger('points_winner')->default(5);
            $table->unsignedTinyInteger('points_draw_hit')->default(7);
            $table->timestamps();
        });

        Schema::create('pool_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('pools')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['owner', 'member'])->default('member');
            $table->unsignedInteger('total_points')->default(0);
            $table->timestamps();
            $table->unique(['pool_id', 'user_id']);
        });

        Schema::create('guesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('match_id')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('pool_id')->constrained('pools')->cascadeOnDelete();
            $table->unsignedTinyInteger('home_score_guess');
            $table->unsignedTinyInteger('away_score_guess');
            $table->unsignedTinyInteger('points_earned')->default(0)->nullable();
            $table->boolean('is_calculated')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'match_id', 'pool_id']);
            $table->index(['pool_id', 'user_id']);
            $table->index(['match_id', 'is_calculated']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guesses');
        Schema::dropIfExists('pool_members');
        Schema::dropIfExists('pools');
    }
};
