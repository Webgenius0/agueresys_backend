<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GodShowResource extends JsonResource
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
            'total_viewers' => $this->when(isset($this->viewers_count), $this->viewers_count),
            'abilities' => $this->abilities,
            'god_roles' => GodRoleResource::collection($this->whenLoaded('godRoles')),
        ];
    }
}
