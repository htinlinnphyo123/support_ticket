<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'file' => $this->file ? Storage::url($this->file) : null,
            'file_type' => $this->file ? pathinfo($this->file, PATHINFO_EXTENSION) : null,
            'created_at' => $this->created_at->diffForHumans(),
            'creator_name' => $this->creator->name,
            'creator_type' => $this->creator->type->value,
        ];
    }
}
