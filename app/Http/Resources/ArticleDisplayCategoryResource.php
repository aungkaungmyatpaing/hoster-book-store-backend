<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDisplayCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $articles = $this->articles->take(4);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => new YearResource($this->year),
            'month' => new MonthResource($this->month),
            'article' => ArticleResource::collection($articles)
        ];
    }
}
