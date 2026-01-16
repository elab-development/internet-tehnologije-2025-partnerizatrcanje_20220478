<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UcesceResource extends JsonResource
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
            'vreme' => $this->vreme,
            'trka' => new TrkaResource($this->whenLoaded('trka')),
            'user' => new UserResource($this->whenLoaded('trkac')),
        ];
    }
}
