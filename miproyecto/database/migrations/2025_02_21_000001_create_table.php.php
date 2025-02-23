<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nombre');
            $table->string('password');
            $table->string('telefono')->nullable();
            $table->timestamps();
        });

        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidadMesa');
            $table->string('codMesa')->unique();
            $table->boolean('ocupada')->default(false);
            $table->timestamps();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('comidas', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('bebidas', function (Blueprint $table) {
            $table->id();
            $table->string('tamanyo');
            $table->string('tipoBebida');
            $table->timestamps();
        });

        Schema::create('cartas', function (Blueprint $table) {
            $table->id();
            $table->string('cod')->unique();
            $table->float('precio');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->boolean('disponible')->default(true);
            $table->float('precio');
            $table->foreignId('carta_id')->constrained('cartas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('codReserva')->unique();
            $table->integer('cantPersona');
            $table->boolean('reservaConfirmada')->default(false);
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('cod')->unique();
            $table->date('fecha');
            $table->string('estado');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('linea_pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->float('precio');
            $table->string('estado');
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('carta_id')->constrained('cartas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('linea_pedidos');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('cartas');
        Schema::dropIfExists('bebidas');
        Schema::dropIfExists('comidas');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('mesas');
        Schema::dropIfExists('usuarios');
    }
};