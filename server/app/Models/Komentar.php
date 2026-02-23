<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Komentar",
    title: "Komentar",
    description: "Komentar model",
    required: ["id", "post_id", "user_id", "komentar", "ocena", "datum_komentara"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "post_id", type: "integer", example: 1),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "komentar", type: "string", example: "Ovo je komentar"),
        new OA\Property(property: "ocena", type: "integer", example: 5),
        new OA\Property(property: "datum_komentara", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T00:00:00Z")
    ]
)]
class Komentar extends Model
{
    protected $table = 'komentari';

    protected $fillable = [
        'post_id',
        'user_id',
        'komentar',
        'ocena',
        'datum_komentara'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
