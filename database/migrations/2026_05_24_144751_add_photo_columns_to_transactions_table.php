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
    Schema::table('transactions', function (Blueprint $table) {
        if (!Schema::hasColumn('transactions', 'transfer_proof')) {
            $table->string('transfer_proof')->nullable()->after('payment_proof');
        }
        if (!Schema::hasColumn('transactions', 'initial_clothes_condition')) {
            $table->string('initial_clothes_condition')->nullable()->after('transfer_proof');
        }
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumnIfExists('transfer_proof');
        $table->dropColumnIfExists('initial_clothes_condition');
    });
}
};
