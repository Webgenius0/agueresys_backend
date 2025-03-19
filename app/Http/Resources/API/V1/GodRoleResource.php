<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GodRoleResource extends JsonResource
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
            'role_id' => $this->role_id,
            'role_name' => $this->role->name,
            'god_id' => $this->god_id,
            'vote_count'=> $this->vote_count,
            'upvotes'=> $this->upvotes,
            'downvotes'=> $this->downvotes,
            'is_voted'=> $this->is_voted,
            'vote_value'=> $this->vote_value
        ];
    }
}
