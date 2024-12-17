<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mf_sms_communication_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->enum('event', ['confirmation', 'cancellation', 'remainder']); 
            $table->boolean('include_patient_name')->default(false); 
            $table->boolean('include_clinic_name')->default(false); 
            $table->boolean('include_contact_number')->default(false);
            $table->boolean('is_enabled')->default(true); 
            $table->timestamps();
        
            $table->foreign('clinic_id')->references('id')->on('mf_clinic_details')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_sms_communication_settings');
    }
};
