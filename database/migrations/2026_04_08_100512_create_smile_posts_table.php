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
     Schema::create('smile_posts', function (Blueprint $table) {
        $table->increments('id');

        $table->string('car', 100);
        $table->string('start_distance', 100);
        $table->string('end_distance', 100);
        $table->string('member', 100);
        $table->string('dates', 100);
        $table->string('user', 100);
        $table->string('departureTime', 100);
        $table->string('arrivalTime', 100);
        $table->string('goingBack', 100);
        $table->string('destination', 100);
        $table->string('any', 100);
        $table->string('shareRide', 100);
        $table->string('classification', 100);
        $table->string('remarks', 100);
        $table->string('distance', 80);
        $table->integer('price');
        $table->dateTime('datetimes');

        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smile_posts');
    }
};
