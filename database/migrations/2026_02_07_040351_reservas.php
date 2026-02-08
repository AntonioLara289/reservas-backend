<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // id_reserva int primary key auto_increment,
    // key_espacio int not null,
    // fecha date not null,
    // hora time not null,
    // key_usuario int not null,
    // descripcion text(1024),
    // estado varchar(64) not null,
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id_reserva');
            $table->integer('key_espacio')->nullable(false);
            $table->dateTime('fecha')->nullable(false);
            $table->dateTime('hora')->nullable(false);
            $table->integer('key_usuario')->nullable(false);
            $table->text('descripcion')->nullable(false);
            $table->tinyInteger('estatus');
            $table->dateTime('created_at')->nullable(false);
            $table->dateTime('updated_at')->nullable(false);
            $table->dateTime('deleted_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
