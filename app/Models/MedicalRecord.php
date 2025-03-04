<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Patients;
use App\Models\MedicalStaff;
use App\Models\Clinic;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalRecordFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'medical_staff_id',
        'appointment_id',
        'clinic_id',
        'diagnosis',
        'symptoms',
        'notes',
        'prescription_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patients::class);
    }

    public function medicalStaff()
    {
        return $this->belongsTo(MedicalStaff::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

}
