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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Product name
            $table->string('slug')->unique(); // SEO-friendly URL
            $table->decimal('price', 10, 2); // Base price
            $table->unsignedInteger('stock')->default(0); // Available stock
            $table->text('description')->nullable(); // Detailed description
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
