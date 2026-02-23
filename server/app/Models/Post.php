<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Post",
    title: "Post",
    description: "Post model",
    required: ["id", "ucesce_id", "sadrzaj", "datum_objave"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "ucesce_id", type: "integer", example: 1),
        new OA\Property(property: "sadrzaj", type: "string", example: "Ovo je sadrzaj posta"),
        new OA\Property(property: "datum_objave", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z")
    ]
)]
class Post extends Model
{
    protected $table = 'postovi';

    protected $fillable = [
        'ucesce_id',
        'sadrzaj',
        'datum_objave',
    ];

    public function ucesce()
    {
        return $this->belongsTo(Ucesce::class, 'ucesce_id');
    }

    public function komentari()
    {
        return $this->hasMany(Komentar::class, 'post_id');
    }
}
