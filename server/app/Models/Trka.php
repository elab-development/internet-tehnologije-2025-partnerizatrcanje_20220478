<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
