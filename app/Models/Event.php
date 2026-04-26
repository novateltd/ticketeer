<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\TicketEnum;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'onsale' => 'date',
        'date' => 'date',
        'capacity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(function (Event $event) {
            $event->syncTicketInventory();
        });
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function promos()
    {
        return $this->hasMany(Promo::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(EventTicketType::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        $query->whereDate('onsale', '<=', today())
            ->whereDate('date', '>=', today())
            ->orderBy('date');
    }

    public function getIsOnsaleAttribute()
    {
        return $this->onsale <= today();
    }

    public function syncTicketInventory(): void
    {
        if (empty($this->capacity)) {
            return;
        }

        $currentCount = $this->tickets()->count();

        if ($currentCount >= $this->capacity) {
            return;
        }

        $padLength = max(3, strlen((string) $this->capacity));

        foreach (range($currentCount + 1, $this->capacity) as $number) {
            $this->tickets()->create([
                'number' => Str::padLeft((string) $number, $padLength, '0'),
                'status' => TicketEnum::AVAILABLE->value,
            ]);
        }
    }
}
