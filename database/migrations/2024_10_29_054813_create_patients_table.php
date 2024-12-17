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
        Schema::create('mf_patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('contact_no');
            $table->string('email_id');
            $table->string('gender');
            $table->timestamp('date_and_time');
            $table->integer('duration');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_patients');
    }
};
