<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_detail', function (Blueprint $table) {
            $table->id();
            
            // Flight segment info
            $table->string('flight_id')->unique();
            $table->string('flight_number');
            $table->string('equipment_type')->nullable();
            $table->integer('stops')->default(0);
            $table->integer('duration');

            // Airline info
            $table->string('airline_code', 10);
            $table->string('airline_name');
            $table->boolean('is_lcc')->default(false);
            $table->string('booking_id')->nullable();

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

            // Departure & arrival times
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');

            // Additional flags
            $table->boolean('is_iand')->default(false);
            $table->boolean('is_rs')->default(false);
            $table->integer('segment_number')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('flight_id');
            $table->index('airline_code');
            $table->index('departure_code');
            $table->index('arrival_code');
              $table->decimal('price', 12, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_detail');
    }
};
