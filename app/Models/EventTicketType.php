<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'integer',
        'minimum' => 'integer',
        'sort_order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
