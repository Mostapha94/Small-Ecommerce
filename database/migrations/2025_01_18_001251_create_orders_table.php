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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User placing the order
            $table->string('customer_phone'); // Customer's phone number
            $table->string('shipping_address'); // Shipping address
            $table->decimal('total_price', 10, 2); // Total price of the order
            $table->enum('status', ['pending', 'completed', 'canceled']); // Order status (e.g., pending, completed, canceled)
            $table->text('notes')->nullable(); // Customer notes
            $table->timestamp('shipped_at')->nullable(); // Shipping date
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
