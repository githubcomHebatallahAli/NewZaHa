<?php

namespace App\Http\Controllers\User;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;

class TeamUserController extends Controller
{
    public function showAll()
    {
         $Teams = Team::with('user')->get();
            return response()->json([
            'data' =>TeamResource::collection($Teams),
            'message' => "Show All Teams Successfully."
        ]);
    }
}
