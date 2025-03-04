<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Medicine extends Model
{
    /** @use HasFactory<\Database\Factories\MedicineFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'medical_category_id',
        'stock',
        'price',
    ];

    public function medicalCategory()
    {
        return $this->belongsTo(MedicalCategory::class, 'medical_category_id');
    }
}
