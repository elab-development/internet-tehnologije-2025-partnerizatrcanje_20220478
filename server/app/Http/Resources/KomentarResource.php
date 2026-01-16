<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KomentarResource extends JsonResource
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
            'post' => new PostResource($this->whenLoaded('post')),
            'user' => new UserResource($this->whenLoaded('user')),
            'komentar' => $this->komentar,
            'ocena' => $this->ocena,
            'datum_komentara' => $this->datum_komentara,
        ];
    }
}
