<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'name',
        'mm_name',
        'month',
        'publish'
    ];

    protected $casts = [
        'publish' => 'boolean'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
