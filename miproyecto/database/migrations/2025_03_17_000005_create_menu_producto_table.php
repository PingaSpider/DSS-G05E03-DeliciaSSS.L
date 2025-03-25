<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('menu_producto', function (Blueprint $table) {
            $table->id();
            $table->string('menu_cod', 5);
            $table->string('producto_cod', 5);
            $table->integer('cantidad')->default(1);
            $table->string('descripcion')->default('');
            $table->timestamps();

            $table->foreign('menu_cod')->references('cod')->on('menus')->onDelete('cascade');
            $table->foreign('producto_cod')->references('cod')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_producto');
    }
};
