<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_destination_records', function (Blueprint $table) {
            $table->id();

            $table->string('user');
            $table->string('destination');

            $table->string('pickup_location')->nullable();

            $table->boolean('dialysis')->default(false);

            $table->integer('transport_fee')->default(0);

            $table->decimal('distance', 5, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_destination_records');
    }
};