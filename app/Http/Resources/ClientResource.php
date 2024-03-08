<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'phoneNumber' => $this->phoneNumber,
            'projectName' => $this->projectName,
            'description' => $this->description,
            'user' => new UserResource($this->user),
            'media' => MediaResource::collection($this->getMedia('Clients')),

        ];
    }
}
