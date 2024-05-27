<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'history_id',
        'month_id',
    ];

    public function history()
    {
        return $this->belongsTo(History::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }
}
