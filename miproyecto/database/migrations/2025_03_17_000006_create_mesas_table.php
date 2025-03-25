<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->string('codMesa', 5)->primary();
            $table->boolean('ocupada')->default(true);
            $table->integer('cantidadMesa')->default(2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mesas');
    }
};
