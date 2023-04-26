<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogResource extends JsonResource
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
            'activity' => $this->activity,
            'billable' => $this->billable,
            'project' => new ProjectResource($this->whenLoaded("project")),
            'start' => $this->start,
            'end' => $this->end,
            'total' => $this->total
        ];
    }
}
