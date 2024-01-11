<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_user_transaction', function (Blueprint $table) {
            $table->double('debit')->after('currency')->default(false);
            $table->double('credit')->after('debit')->default(false);
            $table->double('solde')->after('credit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_user_transaction', function (Blueprint $table) {
            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('solde');
        });
    }
};
