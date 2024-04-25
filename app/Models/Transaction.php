<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\TransactionEnum;

class Transaction extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopePending(Builder $q)
    {
        $q->where('status',TransactionEnum::PENDING)
            ->where('created_at', '>', now()->subMinutes(120));
    }

    public function getTicketsArrayAttribute()
    {
        return json_decode($this->tickets_bought, true);
    }

    public function getAdultTicketsAttribute()
    {
        return collect(json_decode($this->tickets_bought, true))->firstWhere('type','Adult Ticket')['count'];
    }
    
    public function getJuniorTicketsAttribute()
    {
        return collect(json_decode($this->tickets_bought, true))->firstWhere('type','Junior Ticket')['count'];
    }

    public function scopeCompleted(Builder $q): void
    {
        $q->whereNotNull('completion');
    }
}
