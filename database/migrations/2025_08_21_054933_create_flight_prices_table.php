<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flight_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_detail_id');
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->decimal('total_fare', 10, 2)->default(0);
            $table->decimal('net_fare', 10, 2)->default(0);
            $table->decimal('total_taxes', 10, 2)->default(0);
            $table->json('tax_breakdown')->nullable();
            $table->timestamps();

            $table->foreign('flight_detail_id')
                  ->references('id')
                  ->on('flight_detail')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_prices');
    }
};
