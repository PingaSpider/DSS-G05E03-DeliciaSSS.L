<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('linea_pedidos', function (Blueprint $table) {
            $table->string('linea', 5)->primary();
            $table->integer('cantidad');
            $table->float('precio');
            $table->string('estado');
            $table->string('pedido_id', 5);
            $table->string('producto_id', 5);
            $table->timestamps();

            $table->foreign('pedido_id')->references('cod')->on('pedidos')->onDelete('cascade');
            $table->foreign('producto_id')->references('cod')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('linea_pedidos');
    }
};
