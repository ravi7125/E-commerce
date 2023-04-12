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
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');       
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');  
        $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade')->onUpdate('cascade');            
        $table->integer('quantity');
        $table->integer('price');
        $table->string('payment_method');
        $table->string('address');
        $table->string('phone', 10);
        $table->string('city');
        $table->integer('pincode');
        $table->string('status', 10);
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
