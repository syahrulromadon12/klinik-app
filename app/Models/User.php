<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'date_of_birth',
        'address',
        'gender',
        'nik_number',
        'kis_number',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_number',
        'status',
        'email_verified_at',
        'password',
        'role_id',
        'photo_path',
        'remember_token',
        'deleted_at',
    ];

    public function medicalStaff()
    {
        return $this->hasOne(MedicalStaff::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Override method delete untuk juga menghapus data terkait
    public function delete()
    {
        if ($this->doctor) {
            $this->doctor->delete();
        }
        if ($this->nurse) {
            $this->nurse->delete();
        }
        if ($this->pharmacist) {
            $this->pharmacist->delete();
        }

        return parent::delete(); 
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
