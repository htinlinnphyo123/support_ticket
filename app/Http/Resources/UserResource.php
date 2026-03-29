<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => [
                'value' => $this->type?->value,
                'label' => $this->type?->label(),
                'color' => $this->type?->color(),
            ],
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
                'color' => $this->status?->color(),
            ],
            'organisation_id' => $this->organisation_id,
            'organisation' => new OrganisationResource($this->whenLoaded('organisation')),
            'created_at' => $this->created_at,
        ];
    }
}
