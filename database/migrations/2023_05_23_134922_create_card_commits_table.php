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
        Schema::create('card_commits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('board_id')->constrained('boards');
            $table->foreignUuid('branch_id')->constrained('branches');
            $table->foreignUuid('card_id')->constrained('cards');
            $table->foreignUuid('commit_id')->constrained('commits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_commits');
    }
};
