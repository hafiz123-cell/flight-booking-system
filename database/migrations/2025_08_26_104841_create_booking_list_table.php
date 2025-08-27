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
        Schema::create('booking_list', function (Blueprint $table) {
            $table->id();

            // Which user made the booking
            $table->unsignedBigInteger('user_id');

            // Foreign keys to related records
            $table->unsignedBigInteger('flight_detail_id')->nullable();
            $table->string('return_flight_detail_id')->nullable(); 
            $table->unsignedBigInteger('traveller_detail_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();

            // Booking status
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('flight_detail_id')->references('id')->on('flight_detail')->onDelete('set null');
            $table->foreign('traveller_detail_id')->references('id')->on('traveller_detail')->onDelete('set null');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_list');
    }
};
