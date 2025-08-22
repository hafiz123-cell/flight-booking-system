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
       Schema::create('flight_return_detail', function (Blueprint $table) {
    $table->id();

    // Link to onward flight
    $table->unsignedBigInteger('onward_flight_id');
    $table->foreign('onward_flight_id')->references('id')->on('flight_detail')->onDelete('cascade');

    // Flight segment info
    $table->string('flight_id')->unique();
    $table->string('flight_number');
    $table->string('equipment_type')->nullable();
    $table->integer('stops')->default(0);
    $table->integer('duration'); // duration in minutes

    // Airline info
    $table->string('airline_code', 10);
    $table->string('airline_name');
    $table->boolean('is_lcc')->default(false);

    // Departure airport
    $table->string('departure_code', 10);
    $table->string('departure_name');
    $table->string('departure_city')->nullable();
    $table->string('departure_country')->nullable();
    $table->string('departure_terminal')->nullable();
    $table->string('departure_timezone')->nullable();
    $table->decimal('departure_latitude', 10, 6)->nullable();
    $table->decimal('departure_longitude', 10, 6)->nullable();

    // Arrival airport
    $table->string('arrival_code', 10);
    $table->string('arrival_name');
    $table->string('arrival_city')->nullable();
    $table->string('arrival_country')->nullable();
    $table->string('arrival_terminal')->nullable();
    $table->string('arrival_timezone')->nullable();
    $table->decimal('arrival_latitude', 10, 6)->nullable();
    $table->decimal('arrival_longitude', 10, 6)->nullable();

    // Departure and arrival times
    $table->dateTime('departure_time');
    $table->dateTime('arrival_time');

    // Additional flags
    $table->boolean('is_iand')->default(false);
    $table->boolean('is_rs')->default(false);
    $table->integer('segment_number')->default(0);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_return_detail');
    }
};
