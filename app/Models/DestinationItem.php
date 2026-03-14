<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationItem extends Model
{
    protected $fillable = ['nom', 'type', 'destination_id'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
