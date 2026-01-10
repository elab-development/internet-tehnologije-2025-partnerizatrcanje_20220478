<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
