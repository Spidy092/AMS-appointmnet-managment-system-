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
        Schema::create('mf_clinic_doctor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('mf_clinic_details')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('mf_doctor_details')->onDelete('cascade');
            $table->json('specializations');
            $table->timestamps();
        
            $table->unique(['doctor_id', 'clinic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_clinic_doctor');
    }
};
