<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MedicalCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalCategoryFactory> */
    use HasFactory, HasUuids;

    protected $fillable = ['id','name'];
}
