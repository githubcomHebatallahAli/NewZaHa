<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'name' => $this->name,
            'Boss' => $this->Boss,
            'job'=> $this ->job,
            'skills'=> $this ->skills,
            'numProject'=> $this ->numProject,
            'address' => $this->address,
            'phoneNumber' => $this->phoneNumber,
            'qualification'=> $this ->qualification,
            'dateOfJoin' => $this -> dateOfJoin,
            'salary' => $this -> salary,
            'photo' => $this -> photo,
            'imgIDCard' => $this -> imgIDCard,

            'user' => new UserResource($this->user),

        ];
    }
}
