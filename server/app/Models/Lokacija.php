<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Lokacija",
    title: "Lokacija",
    description: "Lokacija model",
    required: ["id", "naziv", "long", "lat"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "naziv", type: "string", example: "Beograd"),
        new OA\Property(property: "long", type: "number", format: "float", example: 20.123456),
        new OA\Property(property: "lat", type: "number", format: "float", example: 44.123456),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z")
    ]
)]
class Lokacija extends Model
{
    protected $table = 'lokacije';

    protected $fillable = [
        'naziv',
        'long',
        'lat',
    ];

    public function trka()
    {
        return $this->hasMany(Trka::class, 'lokacija_id');
    }
}
