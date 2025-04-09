<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gods_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anonymous_user_id')->constrained('anonymous_users')->onDelete('cascade');
            $table->foreignId('god_id')->constrained('gods')->onDelete('cascade'); // The god to be countered
            $table->foreignId('counter_god_id')->constrained('gods')->onDelete('cascade'); // The suggested counter god
            $table->enum('vote', ['up', 'down']);
            $table->timestamps();
            // Prevent duplicate votes per user per match-up
            $table->unique(['anonymous_user_id', 'god_id', 'counter_god_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gods_counters');
    }
};
