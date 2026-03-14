<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    protected $fillable = ['user_id', 'itineraire_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itineraire()
    {
        return $this->belongsTo(Itineraire::class);
    }
}
