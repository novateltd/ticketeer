<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $casts = [
        'onsale' => 'date',
        'date' => 'date',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function getIsOnsaleAttribute()
    {
        return $this->onsale <= today();
    }


}
