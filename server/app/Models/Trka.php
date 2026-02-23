<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Trka",
    title: "Trka",
    description: "Trka model",
    required: ["id", "naziv", "godina", "organizator", "kilometraza", "datum", "lokacija_id"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "naziv", type: "string", example: "Maraton Beograd"),
        new OA\Property(property: "godina", type: "integer", example: 2024),
        new OA\Property(property: "organizator", type: "string", example: "Atletski savez Srbije"),
        new OA\Property(property: "kilometraza", type: "number", format: "float", example: 42.195),
        new OA\Property(property: "datum", type: "string", format: "date-time", example: "2024-10-01T09:00:00Z"),
        new OA\Property(property: "lokacija_id", type: "integer", example: 1),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z")
    ]
)]
class Trka extends Model
{
    protected $table = 'trke';

    protected $fillable = [
        'naziv',
        'godina',
        'organizator',
        'kilometraza',
        'datum',
        'lokacija_id'
    ];

    public function lokacija()
    {
        return $this->belongsTo(Lokacija::class, 'lokacija_id');
    }

    public function ucesca()
    {
        return $this->hasMany(Ucesce::class, 'trka_id');
    }
}
