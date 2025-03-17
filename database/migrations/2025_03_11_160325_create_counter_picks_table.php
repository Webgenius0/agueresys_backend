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
        Schema::create('counter_picks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('god_id')->constrained('gods')->onDelete('cascade'); // The god being countered
            $table->foreignId('counter_god_id')->constrained('gods')->onDelete('cascade'); // The counter pick
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counter_picks');
    }
};
