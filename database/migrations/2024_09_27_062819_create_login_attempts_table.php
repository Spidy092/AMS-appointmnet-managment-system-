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
        Schema::create('mf_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('attempt_ip', 45);
            $table->string('attempt_user', 50);
            $table->integer('attempt_count')->default(0);
            $table->timestamp('attempt_time');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_login_attempts');
    }
};
