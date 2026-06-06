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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_code')->unique();
        $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        $table->decimal('service_unit', 8, 2); 
        $table->integer('total_price');
        $table->string('status')->default('antrian');
        $table->string('payment_method');
        $table->string('payment_status')->default('pending');
        $table->string('payment_proof')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
