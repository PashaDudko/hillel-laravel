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
            $table->string('number', 15)->unique(true);// ToDo сделать уникальнім и подумать про формат
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->string('status', 10)->default('created');
            $table->text('data');
            $table->string('deliver', 10)->default('card');
            $table->string('payment', 10)->default('cash');
            $table->text('comment')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->datetime('expected_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->datetime('received_at')->nullable();
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
