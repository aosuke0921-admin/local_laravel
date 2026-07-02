<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('smile_cancel', function (Blueprint $table) {
            $table->id();

            $table->string('user', 100);
            $table->string('destination', 100)->nullable();

            $table->date('cancel_date');
            $table->dateTime('datetimes')->nullable();

            $table->string('client_name', 100);
            $table->string('receptionist', 100);

            $table->dateTime('input_date')->nullable();
            $table->date('reflection_date')->nullable();

            $table->string('attention', 100)->nullable();
            $table->text('remarks_txt')->nullable();

            $table->string('place', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smile_cancel');
    }
};