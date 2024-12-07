<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('identification');
            $table->string('name');
            $table->string('last_name');
            $table->integer('telephone');
            $table->string('email');
            $table->string('address'); 
            $table->string('department');
            $table->string('municipality');
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('id_role')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('user_registers')->insert([
            'identification'=> 143242112,
            'name'=> 'Juan',
            'last_name'=> 'Perez',
            'telephone'=> 1234,
            'email'=> 'klkqcd@kndl',
            'address'=> 'HOla Mundo',
            'department'=> 'Cauca',
            'municipality'=> 'Popayán',
            'password'=> '12345678',
            'id_role'=> 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_registers');
    }
};
