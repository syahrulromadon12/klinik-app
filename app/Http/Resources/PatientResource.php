<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kis_number' => $this->kis_number,
            'nik_number' => $this->nik_number,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'allergy' => $this->allergy,
            'job' => $this->job,
            'photo_path' => $this->photo_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
