<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year_id',
        'month_id',
        'payment_account_id',
        'subscription_id',
        'price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function historyMonths()
    {
        return $this->hasMany(HistoryMonth::class);
    }

}
