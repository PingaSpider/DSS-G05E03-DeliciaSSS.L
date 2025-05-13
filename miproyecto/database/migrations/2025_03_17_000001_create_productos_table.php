<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->string('cod', 5)->primary();
            $table->float('pvp');
            $table->string('nombre');
            $table->integer('stock');
            $table->boolean('disponible')->default(true);
            $table->float('precioCompra');
            $table->string('imagen_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
