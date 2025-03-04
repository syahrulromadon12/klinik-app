<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'medical_staff' => new MedicalStaffResource($this->whenLoaded('medicalStaff')),
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'diagnosis' => $this->diagnosis,
            'symptoms' => $this->symptoms,
            'notes' => $this->notes,
            'prescription' => new PrescriptionResource($this->whenLoaded('prescription')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
