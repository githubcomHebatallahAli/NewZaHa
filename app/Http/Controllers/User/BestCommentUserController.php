<?php

namespace App\Http\Controllers\User;

use App\Models\BestComment;
use App\Http\Controllers\Controller;
use App\Http\Resources\BestCommentResource;

class BestCommentUserController extends Controller
{
    public function showAll()
    {
        $bestComments = BestComment::with('comment')->get();
        return response()->json([
            'data' => BestCommentResource::collection($bestComments),
            'message' => "Show All Best Comments Successfully."
        ]);
    }
}
