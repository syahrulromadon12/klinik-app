<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PharmacyOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PharmacyOrderFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'prescription_id',
        'pharmacist_id',
        'status',  
    ];
}
