<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'nameProject'=> $this ->nameProject,
            'price'=> $this ->price,
            'condition'=> $this ->condition,
            'description'=> $this ->description,
            'user' => new UserResource($this->user),
        ];
    }
}
