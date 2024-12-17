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
        Schema::table('mf_email_communication_settings', function (Blueprint $table) {
            $table->unique(['clinic_id', 'event'], 'unique_clinic_event');
        });
    }

    // public function down()
    // {
    //     Schema::table('mf_email_communication_settings', function (Blueprint $table) {
    //         $table->dropUnique('unique_clinic_event');
    //     });
    // }
};
