<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MedicalStaff;
use App\Models\Clinic;
use App\Models\Patients;
use App\Models\PrescriptionItem;

class Prescription extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'medical_staff_id',
        'clinic_id',
        'patient_id',
        'diagnosis',
        'notes',
    ];

    public function medicalStaff()
    {
        return $this->belongsTo(MedicalStaff::class, 'medical_staff_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'patient_id'); // Perhatikan singular: 'patient'
    }

    public function prescriptionItems()
{
    return $this->hasMany(PrescriptionItem::class, 'prescription_id');
}

}
