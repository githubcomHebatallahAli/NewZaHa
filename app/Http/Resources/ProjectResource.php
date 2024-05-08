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
            'nameProject' => $this->nameProject,
            'skills'=> $this ->skills,
            'description'=> $this ->description,
            'urlProject'=> $this->urlProject,
                        'users' => $this->users->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'email' => $user->email,
                        'pivot' => $user->pivot->toArray(),
                    ];
                          }),
            'media' => MediaResource::collection($this->getMedia('Projects')),
        ];
    }
}





