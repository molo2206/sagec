<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('t_resssources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('t_resssources');
    }
};
