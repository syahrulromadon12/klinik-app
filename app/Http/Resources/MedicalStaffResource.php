<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalStaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "license_number" => $this->license_number,
            "specialization" => $this->specialization,
            "work_schedule" => $this->work_schedule,
            "experience_years" => $this->experience_years,
            "education" => $this->education,
            "consultation_fee" => $this->consultation_fee,
            "clinic_id" => $this->clinic_id,
        ];
    }
}
