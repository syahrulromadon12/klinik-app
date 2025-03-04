<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
           'id' => $this->id,
           'patient_id' => $this->patient_id, 
           'medical_staff_id' => $this->medical_staff_id,
           'clinic_id' => $this->clinic_id,
           'appointment_date' => $this->appointment_date,
           'status' => $this->status,
           'created_at' => $this->created_at->format('Y-m-d H:i:s'),
           'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
