<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flight_fare_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_detail_id');
            $table->string('rule_type'); // e.g., CANCELLATION, DATECHANGE, NO_SHOW
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('additional_fee', 10, 2)->nullable();
            $table->text('policy_info')->nullable();
            $table->integer('start_time')->nullable(); // hours before flight
            $table->integer('end_time')->nullable();
            $table->json('fare_components')->nullable();
            $table->timestamps();

            $table->foreign('flight_detail_id')
                  ->references('id')
                  ->on('flight_detail')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_fare_rules');
    }
};
