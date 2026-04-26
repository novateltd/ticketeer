<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TicketEnum;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    

    public function scopeAvailable(Builder $q)
    {
        $q->where('status', TicketEnum::AVAILABLE->value);
    }

    public static function ticketsAvailable(Event $event)
    {
        $pending = $event->transactions()->pending()->sum('ticket_count');

        $tickets = $event->tickets()->available()->count();

        return $tickets - $pending;
    }

    public static function hasTicketsAvailable(Event $event, int $number)
    {
        $available = Self::ticketsAvailable($event);

        return $available >= $number;
    }
}
