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
        Schema::create('mf_doctor_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_clinic_id')->constrained('mf_clinic_doctor')->onDelete('cascade'); // Foreign key referencing doctor_clinic
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('morning_from')->nullable();
            $table->time('morning_to')->nullable();
            $table->time('evening_from')->nullable();
            $table->time('evening_to')->nullable();
            $table->timestamps();
        
            $table->unique(['doctor_clinic_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_doctor_timings');
    }
};
