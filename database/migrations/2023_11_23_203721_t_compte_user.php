<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_compte_user', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->string('number_compte');
            $table->foreignUuid('userid')->constrained('t_users')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(false);
            $table->boolean('deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_compte_user');
    }
};
