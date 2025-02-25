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

        Schema::create('productos', function (Blueprint $table) {
            $table->string('cod',5)->primary();
            $table->float('pvp');
            $table->string('nombre');
            $table->integer('stock');
            $table->boolean('disponible')->default(true);
            $table->float('precioCompra');
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
            
            $table->string('cod',5)->primary();
            $table->date('fecha');
            $table->string('estado');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('linea_pedidos', function (Blueprint $table) {
            $table->string('linea',5)->primary();
            $table->integer('cantidad');
            $table->float('precio');
            $table->string('estado');
            $table->string('pedido_id',5);
            $table->foreign('pedido_id')->references('cod')->on('pedidos')->onDelete('cascade');
            $table->string('producto_id',5);
            $table->foreign('producto_id')->references('cod')->on('productos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('linea_pedidos');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('bebidas');
        Schema::dropIfExists('comidas');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('mesas');
        Schema::dropIfExists('usuarios');
    }
};