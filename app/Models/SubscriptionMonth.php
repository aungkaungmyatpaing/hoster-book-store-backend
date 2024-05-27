<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'month_id',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
