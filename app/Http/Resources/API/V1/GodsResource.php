<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GodsResource extends JsonResource
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
            'sub_title' => $this->sub_title,
            'description_title' => $this->description_title,
            'description' => $this->description,
            'aspect_description' => $this->aspect_description,
            'thumbnail' => $this->thumbnail,
            'max_vote_count' => $this->max_vote_count
        ];
    }
}
