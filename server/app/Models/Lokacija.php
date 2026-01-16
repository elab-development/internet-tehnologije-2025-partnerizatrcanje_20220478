<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
