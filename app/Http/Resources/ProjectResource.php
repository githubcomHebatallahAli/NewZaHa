<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'nameProject' => $this->address,
            'skills'=> $this ->skills,
            'numberSales' => $this->pivot->numberSales,
            'price' => $this->pivot->price,
            'startingDate' => $this->pivot->startingDate,
            'endingDate' => $this->pivot->endingDate,
            'nameOfTeam' => $this->pivot->nameOfTeam,
            'user' => new UserResource($this->user),
            'media' => MediaResource::collection($this->getMedia('Teams')),

        ];
    }
}
