<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Prescription extends Model
{
    /** @use HasFactory<\Database\Factories\PrescriptionFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'patient_id',
        'diagnosis',
        'notes',
    ];
}
