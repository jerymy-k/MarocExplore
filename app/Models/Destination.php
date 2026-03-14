<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['nom', 'logement', 'itineraire_id'];

    public function itineraire()
    {
        return $this->belongsTo(Itineraire::class);
    }

    public function items()
    {
        return $this->hasMany(DestinationItem::class);
    }
}
