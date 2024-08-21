<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name_uz' => $this->name_uz,
            'name_ru' => $this->name_ru,
            'name_en' => $this->name_en,
            'slug' => $this->slug ,
            'year' => $this->year,
            'breeder' => $this->breeder,
            'latest' => $this->latest,
            'color' => $this->color,
            'petal' => $this->petal,
            'height' => $this->height,
            'smell' => $this->smell,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'yesorno' => $this->yesorno,
            'about' => $this->about,
            'created_at' => $this->created_at,
            'seo_title' => $this->seo_title,
            'seo_tag' => $this->seo_tag,
            'seo_description' => $this->seo_description,
            'images' => ImageResource::collection($this->images),
            'categories' => $this->categories->toArray(),
        ];
    }
}
