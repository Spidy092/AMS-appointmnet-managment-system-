<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mf_clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('mf_clinic_details')->onDelete('cascade');
            $table->integer('time_slot_minutes')->default(30); 
            $table->boolean('remainder_to_patient')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mf_clinic_settings');
    }
};
