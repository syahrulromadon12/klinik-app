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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('medical_staff_id')->constrained('medical_staff')->onDelete('cascade');
            $table->foreignUuid('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('clinic_id')->constrained()->onDelete('cascade'); 
            $table->text('diagnosis');
            $table->text('symptoms'); 
            $table->text('notes')->nullable();
            $table->foreignUuid('prescription_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
