<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'photo_path' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'nik_number' => $this->nik_number,
            'kis_number' => $this->kis_number,
            'blood_type' => $this->blood_type,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'insurance_number' => $this->insurance_number,
            'status' => $this->status,
            'role' => new RoleResource($this->whenLoaded('role')),
            'medical_staff' => new MedicalStaffResource($this->whenLoaded('medicalStaff')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
