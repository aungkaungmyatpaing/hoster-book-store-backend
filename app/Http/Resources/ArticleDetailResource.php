<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->getFirstMediaUrl('article-image'),
            'content' => $this->content,
            'year' => new YearResource($this->year),
            'month' => new MonthResource($this->month),
            'category' => new CategoryResource($this->category),
        ];
    }
}
