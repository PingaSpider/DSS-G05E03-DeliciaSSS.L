<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('codReserva')->unique();
            $table->integer('cantPersona');
            $table->boolean('reservaConfirmada')->default(false);
            $table->string('mesa_id', 5);
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('mesa_id')->references('codMesa')->on('mesas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');

            $table->unique(['mesa_id', 'fecha', 'hora']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
};
