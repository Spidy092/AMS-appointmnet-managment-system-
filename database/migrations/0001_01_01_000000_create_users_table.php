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
        Schema::create('mf_users', function (Blueprint $table) {
            $table->id();
            $table->string('name',60);
            $table->string('email',60)->unique();
            $table->string('phone_no',15)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password',150);
            $table->integer('access_group_id')->nullable();
            $table->enum('is_super_admin', ['Y', 'N'])->default('N');
            $table->enum('user_type', ['admin', 'doctor','staff'])->default('admin');
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('added_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_modified')->useCurrent()->nullable();
            $table->string('ip_added', 50);
            $table->string('ip_modified', 50)->nullable();
            $table->string('http_user_agent', 150);
        });

        Schema::create('mf_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('mf_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_users');
        Schema::dropIfExists('mf_password_reset_tokens');
        Schema::dropIfExists('mf_sessions');
    }
};
