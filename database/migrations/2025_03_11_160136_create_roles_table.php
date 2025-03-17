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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Carry, Mid, Support, Solo, Jungle
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
        // Insert predefined roles
        DB::table('roles')->insert([
            ['name' => 'Carry'],
            ['name' => 'Mid'],
            ['name' => 'Support'],
            ['name' => 'Solo'],
            ['name' => 'Jungle'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
