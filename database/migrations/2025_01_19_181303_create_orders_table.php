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
            $table->unsignedSmallInteger('number');// ToDo сделать уникальнім и подумать про формат
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->string('status', 10)->default('created');
            $table->text('data');
            $table->string('deliver', 10)->default('card');
            $table->string('payment', 10)->default('cash');
            $table->text('comment')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->datetime('expected_at')->default('2026-02-16 17:15:21');
            $table->datetime('delivered_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
