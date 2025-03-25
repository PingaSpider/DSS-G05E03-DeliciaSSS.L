<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comidas', function (Blueprint $table) {
            $table->string('cod', 5)->primary();
            $table->string('descripcion');
            $table->timestamps();

            $table->foreign('cod')->references('cod')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comidas');
    }
};
