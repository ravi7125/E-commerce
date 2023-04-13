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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable('N/A');
            $table->unsignedInteger('pincode')->nullable();
            $table->string('address', 30)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('role')->default('user');
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('users');
    // }
    public function down(): void
    {
        Schema::table('users' ,function(Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
