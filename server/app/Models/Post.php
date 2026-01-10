<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
