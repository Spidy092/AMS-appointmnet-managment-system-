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
        Schema::create('mf_staff_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_profile_id')->constrained('mf_staff_profile')->onDelete('cascade');
            $table->enum('categories', ['dashboard','appointments', 'clinics', 'doctors', 'reports', 'settings']);
            $table->boolean('view')->default(false);
            $table->boolean('add')->default(false);
            $table->boolean('edit')->default(false);
            $table->boolean('delete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_staff_access');
    }
};
