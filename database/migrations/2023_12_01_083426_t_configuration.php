<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('t_configuration', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->string('organisation_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('rccm')->nullable();
            $table->string('idnat')->nullable();
            $table->string('num_impot')->nullable();
            $table->float('taux_interet')->nullable();
            $table->string('taux_penalite')->nullable();
            $table->text('logo')->nullable();
            $table->text('fiveicone')->nullable();
            $table->text('politique')->nullable();
            $table->text('condition')->nullable();
            $table->text('apropos')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(false);
            $table->boolean('deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_configuration');
    }
};
