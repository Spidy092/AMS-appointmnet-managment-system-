<?php

use App\Constants\Constants;
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
        Schema::table(Constants::DB_PREFIX . '_clinic_details', function (Blueprint $table) {
            $table->json('specialization_ids')->nullable()->after('latitude');
        });
    }
    
    public function down()
    {
        Schema::table(Constants::DB_PREFIX . '_clinic_details', function (Blueprint $table) {
            $table->dropColumn('specialization_ids');
        });
    }
};
