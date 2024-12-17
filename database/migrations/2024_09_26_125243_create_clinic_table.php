<?php

use App\Models\User;
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
        Schema::create('mf_clinic_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdfor(User::class)->onDelete('cascade');
            $table->string('clinic_name', 60);
            $table->string('clinic_tag_line', 225)->nullable();
            $table->string('contact_no_1', 15);
            $table->string('contact_no_2', 15)->nullable();
            $table->string('gstin', 16)->nullable();
            $table->string('about_clinic')->nullable();
            $table->string('web_address')->nullable();

            $table->string('address', 255);
            $table->string('country', 60);
            $table->string('state', 60);
            $table->string('district', 60);
            $table->string('locality', 60);
            $table->string('pincode', 6);
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();

            $table->enum('fees_based_on', ["nofee","specificationBased","clinicBased"])->default('nofee');
            $table->integer('consultation_fee')->nullable();

            $table->string('logo_url')->nullable();
            $table->string('clinic_image1')->nullable();
            $table->string('clinic_image1_thumb')->nullable();
            $table->string('clinic_image2')->nullable();
            $table->string('clinic_image2_thumb')->nullable();
            $table->string('clinic_image3')->nullable();
            $table->string('clinic_image3_thumb')->nullable();
            $table->string('clinic_image4')->nullable();
            $table->string('clinic_image4_thumb')->nullable();
            $table->string('clinic_image5')->nullable();
            $table->string('clinic_image5_thumb')->nullable();

            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('added_by');
            $table->integer('modified_by')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_modified')->useCurrent()->nullable();
            $table->string('ip_address', 50);
            $table->string('ip_modified', 50)->nullable();



            $table->string('communication_email')->nullable(); 
            $table->string('communication_contact_number', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('mf_clinic_details');
    }
};
