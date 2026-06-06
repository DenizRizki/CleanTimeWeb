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
        $table->unsignedBigInteger('service_id')->nullable()->change();
        
        if (Schema::hasColumn('transactions', 'service_unit')) {
            $table->decimal('service_unit', 8, 2)->nullable()->change();
        }
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->unsignedBigInteger('service_id')->nullable(false)->change();
        $table->decimal('service_unit', 8, 2)->nullable(false)->change();
    });
}
};
