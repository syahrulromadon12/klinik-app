<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Queue extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'queue_number',
        'clinic_id',
        'status',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
