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
            // 'skills' => $this->skills,
            'skills' => $this->skills ? explode(',', $this->skills) : [],
            'description' => $this->description,
            'price' => $this->price,
            'saleType' => $this->saleType,
            'urlProject' => $this->urlProject,
            'imgProject' => json_decode($this->imgProject),
            'startingDate' => $this->startingDate,
            'endingDate' => $this->endingDate,
            // 'team' => $this->team,
            // 'team' => $this->team ? explode(',', $this->team) : [],
            'team_id' => $this->team_id, // إضافة معرف الفريق
            'team' => new TeamResource($this->whenLoaded('team')),


            'users' => $this->users->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'price' => $user->pivot->price,
                    'numberOfSales' => $user->pivot->numberOfSales,
                ];
            })->toArray(),
        ];
}
}





