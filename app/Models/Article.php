<?php

namespace App\Models;

use App\Traits\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    use NotificationService;

    protected $fillable = [
        'year_id',
        'month_id',
        'category_id',
        'title',
        'content'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article-image')
            ->useFallbackUrl(asset('/assets/images/default.png'))
            ->acceptsFile(function (File $file) {
                $allowedMimeTypes = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/webp',
                ];
                return in_array($file->mimeType, $allowedMimeTypes);
            })
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            });
    }

    protected static function booted()
    {
        static::created(function (Article $article) {
           if ($article->month && $article->month->publish == true) {
                $userFcmTokens = $article->getUserFcmTokens();
                foreach ($userFcmTokens as $token ) {
                    $article->sendNotification(
                        $token,
                        "New Article Created!",
                        "A new article has been added to the app: " . $article->title
                    );

                    $user = User::where('fcm_token', $token)->first();
                    if ($user) {
                        UserNotification::create([
                            'user_id' => $user->id,
                            'title' => "New Article Created!",
                            'body' => "A article song has been added to the app: " . $article->title,
                            'new' => true,
                        ]);
                    }
                }
           }

        });
    }

    public function getUserFcmTokens(): array
    {
        $userFcmTokens = [];

        $users = User::all();
        foreach ($users as $user) {
            $userFcmTokens[] = $user->fcm_token;
        }
        return $userFcmTokens;
    }

}
