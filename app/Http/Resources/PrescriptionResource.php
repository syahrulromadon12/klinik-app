<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'medical_staff' => new MedicalStaffResource($this->whenLoaded('medicalStaff')),
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'prescription_items' => PrescriptionItemResource::collection($this->whenLoaded('prescriptionItems')),
            'diagnosis' => $this->diagnosis,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
