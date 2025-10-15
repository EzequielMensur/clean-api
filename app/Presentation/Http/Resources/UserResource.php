<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'posts_count' => $this->when(isset($this->posts_count), $this->posts_count),
            'created_at' => optional($this->created_at)?->toAtomString(),
            'updated_at' => optional($this->updated_at)?->toAtomString(),
        ];
    }
}
