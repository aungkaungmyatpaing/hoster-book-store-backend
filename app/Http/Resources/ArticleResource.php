<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::guard('user')->user();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('year_id', $this->year_id)
            ->where('status', 'approve')
            ->first();
        $subscripeMonth = null;
        if ($subscription) {
            $subscripeMonth = $subscription->subscriptionMonths->where('month_id', $this->month_id)->first();
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->getFirstMediaUrl('article-image'),
            'content' => $this->content,
            'year' => $this->year->name,
            'month' => $this->month->name,
            'category' => $this->category->name,
            'has_subscription' => $subscripeMonth !== null ? 'true' : 'false',
        ];
    }
}
