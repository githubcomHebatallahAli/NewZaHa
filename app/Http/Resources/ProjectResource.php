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
            'skills' => $this->skills,
            'description' => $this->description,
            'users' => $this->users->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'pivot' => array_merge(
                        $user->pivot->toArray(),
                        ['imgProject' => json_decode($user->pivot->imgProject)] // تحويل imgProject إلى JSON
                    ),
                ];
            }),
        ];
    }
}





