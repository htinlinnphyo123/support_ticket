<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
                'color' => $this->status?->color(),
            ],
            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at,
        ];
    }
}
