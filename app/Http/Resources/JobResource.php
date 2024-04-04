<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'realName'=> $this->realName,
            'address' => $this->address,
            'phoneNumber' => $this->phoneNumber,
            'qualification'=> $this ->qualification,
            'job'=> $this ->job,
            'yearsOfExperience'=> $this ->yearsOfExperience,
            'skills'=> $this ->skills,
            'user' => new UserResource($this->user),

        ];
    }
}
