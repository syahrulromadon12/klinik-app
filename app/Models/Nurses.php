<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nurses extends Model
{
    /** @use HasFactory<\Database\Factories\NursesFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip_number',
        'str_number',
        'date_of_birth',
        'gender',
        'photo_path',
    ];
}
