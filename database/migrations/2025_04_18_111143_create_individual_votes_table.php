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
        Schema::create('individual_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anonymous_user_id')->constrained('anonymous_users')->onDelete('cascade');
            $table->foreignId('god_role_id')->constrained('god_roles')->onDelete('cascade');
            $table->enum('vote', ['up', 'down']); // Upvote or Downvote
            $table->timestamps();

            // Prevent duplicate votes for the same god-role by the same user
            $table->unique(['anonymous_user_id', 'god_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_votes');
    }
};
