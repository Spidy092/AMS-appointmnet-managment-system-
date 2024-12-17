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
        Schema::create('mf_email_communication_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id');
            $table->enum('event', ['confirmation', 'cancellation', 'remainder']);
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
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
        Schema::dropIfExists('mf_email_communication_settings');
    }
};
