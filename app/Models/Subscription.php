<?php

namespace App\Models;

use App\Traits\NotificationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Subscription extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    use NotificationService;

    protected $fillable = [
        'user_id',
        'year_id',
        'payment_account_id',
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

    public function subscriptionMonths()
    {
        return $this->hasMany(SubscriptionMonth::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment-slip')
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
        static::created(function (Subscription $Subscription) {
            $token = $Subscription->user->fcm_token;
            if ($token) {
                $Subscription->sendNotification(
                    $token,
                    "Payment Success!",
                    "Your payment is successful, please wait approval for your subscription actvie."
                );

                $user = User::where('id', $Subscription->user->id)->where('fcm_token', $token)->first();
                if ($user) {
                    UserNotification::create([
                        'user_id' => $user->id,
                        'title' => "Payment Success!",
                        'body' =>    "Your payment is successful, please wait approval for your subscription actvie.",
                        'new' => true,
                    ]);
                }
            }
        });

        static::updated(function (Subscription $Subscription) {
            $oldStatus = $Subscription->getOriginal('status');
            if ($Subscription->isDirty('status') && $oldStatus !== $Subscription->status) {
                $token = $Subscription->user->fcm_token;
                if ($token) {
                    $Subscription->sendNotification(
                        $token,
                        "Subscription " . $Subscription->status ."!",
                        "Your subscription have been " . $Subscription->status . " by admin."
                    );

                    $user = User::where('id', $Subscription->user->id)->where('fcm_token', $token)->first();
                    if ($user) {
                        UserNotification::create([
                            'user_id' => $user->id,
                            'title' => "Subscription " . $Subscription->status ."!",
                            'body' =>  "Your subscription have been " . $Subscription->status . " by admin.",
                            'new' => true,
                        ]);
                    }

                    if ($Subscription->status == 'approve') {
                        $old_History = History::where('subscription_id',$Subscription->id)->first();
                        if (!$old_History) {
                           $newHistory = History::create([
                                'user_id' => $Subscription->user_id,
                                'subscription_id' => $Subscription->id,
                                'year_id' => $Subscription->year_id,
                                'payment_account_id' => $Subscription->payment_account_id,
                                'price' => $Subscription->price,
                                'status' => 'approve'
                            ]);

                            $months = $Subscription->subscriptionMonths;
                            foreach ($months as $key => $value) {
                                HistoryMonth::create([
                                    'history_id' => $newHistory->id,
                                    'month_id' => $value->month_id,
                                ]);
                            }


                        }
                    }

                    if ($Subscription->status == 'cancel') {
                        $old_History = History::where('subscription_id',$Subscription->id)->first();
                        if ($old_History) {
                            $old_History->historyMonths()->delete();
                            $old_History->delete();

                        }
                    }
                }

            }

        });
    }
}
