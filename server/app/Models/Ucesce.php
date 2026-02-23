<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Ucesce",
    title: "Ucesce",
    description: "Ucesce model",
    required: ["id", "trka_id", "user_id", "vreme"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "trka_id", type: "integer", example: 1),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "vreme", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z")
    ]
)]
class Ucesce extends Model
{
    protected $table = 'ucesca';

    protected $fillable = [
        'trka_id',
        'user_id',
        'vreme',
    ];

    public function trka()
    {
        return $this->belongsTo(Trka::class, 'trka_id');
    }

    public function trkac()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postovi()
    {
        return $this->hasMany(Post::class,  'ucesce_id');
    }
}
