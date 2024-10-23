<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'details' => $this->resource->details,
            'isCompleted' => $this->resource->is_completed,
            'deadline' => $this->resource->deadline,
            'createdAt' => $this->resource->created_at
        ];
    }
}
