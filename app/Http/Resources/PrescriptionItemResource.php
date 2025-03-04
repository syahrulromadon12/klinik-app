<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'medicine' => [
                'id' => $this->medicine->id,
                'name' => $this->medicine->name,
            ],
            'quantity' => $this->quantity,
        ];
    }
}
