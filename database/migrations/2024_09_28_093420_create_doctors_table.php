<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mf_doctor_details', function (Blueprint $table) {
            $table->id();
            // $table->string('first_name',60);
            // $table->string('last_name',60)->nullable();
            $table->foreignIdfor(User::class)->onDelete('cascade'); 
            // $table->json('clinic_ids')->nullable();
            $table->string('about_me',225)->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            // $table->string('email',60)->nullable();
            $table->string('alt_email',60)->nullable();
            // $table->string('phone',15)->nullable();
            $table->string('alt_phone',15)->nullable();
            $table->date('dob')->nullable();

            $table->string('pan_number',10)->nullable();
            $table->string('education')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('role')->nullable();
            $table->float('years_of_experience')->nullable();
            $table->json('specializations')->nullable();

            $table->string('address', 255)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('state', 60)->nullable();
            $table->string('district', 60)->nullable();
            $table->string('locality', 60)->nullable();
            $table->string('pincode', 6)->nullable();

            $table->timestamp('last_login')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('added_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_modified')->useCurrent()->nullable();
            $table->string('ip_added', 50);
            $table->string('ip_modified', 50)->nullable();
            $table->string('http_user_agent', 150);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_doctor_details');
    }
};
