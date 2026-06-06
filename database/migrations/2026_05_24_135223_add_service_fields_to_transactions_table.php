<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'service_id')) {
                $table->foreignId('service_id')->after('customer_id')->constrained('services')->onDelete('cascade');
            }
            if (!Schema::hasColumn('transactions', 'service_unit')) {
                $table->decimal('service_unit', 8, 2)->after('service_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id', 'service_unit']);
        });
    }
};