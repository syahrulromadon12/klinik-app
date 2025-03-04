<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new PatientResource($this->patient),
            'medical_staff' => new MedicalStaffResource($this->medicalStaff),
            'appointment' => new AppointmentResource($this->appointment),
            'clinic' => new ClinicResource($this->clinic),
            'diagnosis' => $this->diagnosis,
            'symptoms' => $this->symptoms,
            'notes' => $this->notes,
            'prescription' => new PrescriptionResource($this->prescription),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
