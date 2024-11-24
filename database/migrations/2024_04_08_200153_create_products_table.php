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
            $table->string('name', 30)->unique();
            $table->string('slug', 30)->unique();
            $table->string('description')->unique();
            $table->integer('category_id');
            $table->string('SKU', 10)->unique();
            $table->string('price', 10)->default(1);
            $table->string('discount',2 )->default(0);
            $table->string('quantity',5 )->default(1);
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
