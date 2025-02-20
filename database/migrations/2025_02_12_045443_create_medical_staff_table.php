<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('specialization')->nullable();
            $table->json('work_schedule')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('education')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->foreignUuid('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_staff');
    }
};
