<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patients extends Model
{
    /** @use HasFactory<\Database\Factories\PatientsFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'kis_number',
        'nik_number',
        'name',
        'email', 
        'phone_number', 
        'date_of_birth', 
        'address',
        'gender', 
        'blood_type', 
        'allergy', 
        'job', 
        'photo_path',
    ];

}
