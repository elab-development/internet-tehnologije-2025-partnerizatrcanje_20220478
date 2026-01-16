<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrkaResource extends JsonResource
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
            'naziv' => $this->naziv,
            'godina' => $this->godina,
            'organizator' => $this->organizator,
            'kilometraza' => $this->kilometraza,
            'datum' => $this->datum,
            'lokacija' => new LokacijaResource($this->whenLoaded('lokacija')),
        ];
    }
}
