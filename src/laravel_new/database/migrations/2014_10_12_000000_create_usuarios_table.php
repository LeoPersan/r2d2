<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->text('permissoes')->nullable();
            $table->string('senha');
            $table->boolean('ativo');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('usuarios')->insert([
            'nome' => 'Admin',
            'email' => 'admin@admin.com',
            'permissoes' => json_encode(['Admin']),
            'senha' => Hash::make('caw123'),
            'ativo' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
