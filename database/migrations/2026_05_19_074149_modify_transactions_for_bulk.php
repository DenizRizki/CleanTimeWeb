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
            if (Schema::hasColumn('transactions', 'service_id')) {
                $table->dropForeign('transactions_service_id_foreign');
                $table->dropColumn('service_id');
            }
            
            if (Schema::hasColumn('transactions', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable();
            $table->integer('weight')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }
};