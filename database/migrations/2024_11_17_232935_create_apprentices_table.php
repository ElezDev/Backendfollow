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
        Schema::create('apprentices', function (Blueprint $table) {
            $table->id();
            $table->string('academic_level');
            $table->string('program');
            $table->integer('ficha');
            $table->foreignId('user_id')->references('id')->on('users');
            // $table->foreignId('id_user_register')->references('id')->on('user_registers');
            $table->foreignId('id_contract')->references('id')->on('contracts');
            $table->foreignId('id_trainer')->references('id')->on('trainers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprentices');
    }
};
