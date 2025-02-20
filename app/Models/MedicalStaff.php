<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalStaff extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'date_of_birth',
        'gender', 
        'clinic_id', 
        'work_schedule',
        'experience_years',
        'education',
        'consultation_fee'
    ];

    protected $casts = [
        'work_schedule' => 'array'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}

