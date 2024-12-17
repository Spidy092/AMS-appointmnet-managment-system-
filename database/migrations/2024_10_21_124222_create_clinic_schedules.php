<?php

use App\Models\ClinicDetail;
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
        Schema::create('mf_clinic_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdfor(ClinicDetail::class);
            $table->string('day');
            $table->time('morning_from')->nullable();
            $table->time('morning_to')->nullable();
            $table->time('evening_from')->nullable();
            $table->time('evening_to')->nullable();
            $table->boolean('is_open')->default(0);
            $table->timestamps();

            $table->unique(['clinic_detail_id', 'day'], 'unique_clinic_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_clinic_timings');
    }

};
