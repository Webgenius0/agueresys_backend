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
        Schema::create('god_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('god_id')->constrained()->onDelete('cascade');
            $table->foreignId('anonymous_user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('fingerprint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('god_views');
    }
};
