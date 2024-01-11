<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // `id` char(36) NOT NULL,
    // `designation` varchar(1000) NOT NULL,
    // `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=PENDING | 1=APPROVED | 2=REJECTED ',
    // `currency` varchar(255) NOT NULL,
    // `type` enum('credit','debit') NOT NULL,
    // `method` enum('wallet','mobile_money') DEFAULT 'mobile_money',
    // `number` varchar(255) DEFAULT NULL,
    // `amount` double(255,2) NOT NULL DEFAULT 0.00,
    // `user_id` char(36) NOT NULL,
    // `created_at` timestamp NULL DEFAULT NULL,
    // `updated_at` timestamp NULL DEFAULT NULL
    public function up()
    {
        Schema::create('t_user_transaction', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->string('designation');
            $table->string('currency');
            $table->enum('type', ['credit', 'debit']);
            $table->enum('method', ['wallet', 'mobile_money'])->nullable();
            $table->double('amount');
            $table->string('count_number');
            $table->foreignUuid('userid')->constrained('t_users')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(false);
            $table->boolean('deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_user_transaction');
    }
};
