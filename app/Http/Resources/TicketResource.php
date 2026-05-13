<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'description' => $this->description,
            'link' => $this->link,
            'file_type' => $this->file_type,
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
                'color' => $this->status?->color(),
            ],
            'priority' => [
                'value' => $this->priority?->value,
                'label' => $this->priority?->label(),
                'color' => $this->priority?->color(),
            ],
            'sla_status' => [
                'value' => $this->sla_status->value,
                'label' => $this->sla_status->label(),
                'color' => $this->sla_status->color(),
            ],
            'due_date' => $this->due_date ? $this->due_date->diffForHumans() : null,
            'creator_id' => $this->creator_id,
            'agent_id' => $this->agent_id,
            'agent' => $this->whenLoaded('agent'),
            'creator_name' => $this->whenLoaded('creator')->name,
            'agent_name' => $this->whenLoaded('agent')?->name,
            'organisation_name' => $this->whenLoaded('creator')->organisation->name,
            'created_at' => $this->created_at->diffForHumans(),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
