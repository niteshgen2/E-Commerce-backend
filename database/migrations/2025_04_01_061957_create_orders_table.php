<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to users table
        $table->decimal('total_amount', 10, 2);  // Total amount with 2 decimal points
        $table->enum('order_status', ['pending', 'completed', 'cancelled']);
        $table->enum('payment_status', ['pending', 'paid', 'failed']);
        $table->enum('shipping_status', ['pending', 'shipped', 'delivered']);
        $table->timestamp('placed_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
