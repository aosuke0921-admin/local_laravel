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
        Schema::create('smile_yoyaku', function (Blueprint $table) {
            $table->id();

            $table->string('user');
            $table->string('destination')->nullable();

            $table->dateTime('reservation_datetime');

            $table->string('client_name');
            $table->string('receptionist');

            $table->dateTime('input_date')->nullable();

            $table->text('attention')->nullable();
            $table->text('remarks_txt')->nullable();

            $table->string('place');

            $table->boolean('is_cancel')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smile_yoyaku');
    }
};
