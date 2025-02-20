<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $note = "Silahkan tunggu sampai nomor antrian Anda dipanggil";
        return [
            'id' => $this->id,
            'queue_number' => $this->queue_number,
            'status' => $this->status,
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'note' => $note,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
