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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->string('txnid');
        $table->string('easepayid')->nullable();
        $table->string('status');
        $table->decimal('amount', 10, 2);
        $table->string('productinfo')->nullable();
        $table->string('firstname')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->json('raw_response')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
