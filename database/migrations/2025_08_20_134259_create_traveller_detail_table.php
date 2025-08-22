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
        Schema::create('traveller_detail', function (Blueprint $table) {
            $table->id();

            // Link to booking
            $table->string('booking_id')->index()->nullable();

            // Link to price / multiple passengers
            $table->json('price_id'); // ✅ changed to json (can store multiple priceIds)

            // Link to flight
            $table->unsignedBigInteger('flight_detail_id')->nullable()->index();

            // Passenger info
            $table->json('passenger_data'); // ✅ store multiple passengers as JSON
            $table->boolean('add_to_traveller_list')->default(false);

            // Contact info (usually for primary passenger)
            $table->string('country_code', 10)->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('email', 100)->nullable();

            // GST / Company info
            $table->string('gst_number', 50)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('company_phone', 20)->nullable();
            $table->text('company_address')->nullable();
            $table->boolean('save_gst_details')->default(false);

            $table->timestamps();

            // ✅ set up foreign key to flight_detail
            $table->foreign('flight_detail_id')
                  ->references('id')->on('flight_detail')
                  ->onDelete('cascade'); // delete traveller details if flight is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveller_detail');
    }
};
