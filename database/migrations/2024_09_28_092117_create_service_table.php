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
        Schema::create('mf_specialization_master', function (Blueprint $table) {
            $table->id();
            $table->string('specialization_name', 50);
            $table->foreignId('parent_id')->nullable()->constrained('mf_specialization_master')->nullOnDelete();
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('added_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_modified')->useCurrent()->nullable();
            $table->string('ip_address', 50);
            $table->string('ip_modified', 50)->nullable();
        });

        // Schema::create('mf_treatments_master', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('treatment_name', 50);
        //     $table->unsignedBigInteger('specialization_id');
        //     $table->enum('status', ['0', '1'])->default('1');
        //     $table->integer('parent_id')->nullable();
        //     $table->integer('added_by')->nullable();
        //     $table->integer('modified_by')->nullable();
        //     $table->timestamp('date_added')->useCurrent();
        //     $table->timestamp('date_modified')->useCurrent()->nullable();
        //     $table->string('ip_address', 50);
        //     $table->string('ip_modified', 50)->nullable();

        //     $table->foreign('specialization_id')
        //         ->references('id')
        //         ->on('mf_specialization_master')
        //         ->onDelete('restrict')
        //         ->onUpdate('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('mf_treatments_master');
        Schema::dropIfExists('mf_specialization_master');
    }
};
