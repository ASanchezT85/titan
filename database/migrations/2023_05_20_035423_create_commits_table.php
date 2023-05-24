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
        Schema::create('commits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('board_id')->constrained('boards');
            $table->foreignUuid('developer_id')->constrained('developers');
            $table->integer('number')->nullable();
            $table->string('base')->nullable();
            $table->string('title')->nullable();
            $table->string('sha')->nullable();
            $table->string('node_id');
            $table->string('html_url');
            $table->longText('body')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commits');
    }
};
