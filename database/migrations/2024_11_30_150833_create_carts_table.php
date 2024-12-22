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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->uuid();//ToDo add index
            $table->integer('user_id')->nullable(true);
            $table->integer('product_id'); //ToDo add delete cascade
            $table->integer('quantity')->default(1); //ToDo add delete cascade
            $table->string('status', 10)->default('open');
            $table->string('data')->nullable(true);;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
