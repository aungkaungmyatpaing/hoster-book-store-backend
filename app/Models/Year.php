<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mm_name',
        'year'
    ];

    public function months()
    {
        return $this->hasMany(Month::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
