<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');

        $usersWithComments = User::whereHas('comments')->get();

        $usersArray = $usersWithComments->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'comments' => $user->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                            ];
                        }),
                    ];
        })->toArray();

        return response()->json([
            'data' => $usersArray,
            'message' => "Show All Users with Comments Successfully."
        ]);
    }


    public function create(CommentRequest $request)
    {
        $this->authorize('manage_users');
           $Comment =Comment::create ([
                'comment' => $request->comment,
                'user_id' => $request->user_id,
            ]);
           $Comment->save();
           return response()->json([
            'data' =>new CommentResource($Comment),
            'message' => "Comment Created Successfully."
        ]);

        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
    $Comment = Comment::with('user.Comments')->find($id);
    if (!$Comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }
    $userComments = $Comment->user->Comments->pluck('comment')->toArray();
    return response()->json([
        'data' =>new CommentResource($Comment),
        'user_comments' => ($userComments),
        'message' => "Show Comment for User Successfully."
    ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $Comment = Comment::with('user.Comments')->find($id);
        if (!$Comment) {
            return response()->json([
                'message' => "Comment not found."
            ], 404);
        }
        $userComments = $Comment->user->Comments->pluck('comment')->toArray();
        return response()->json([
            'data' =>new CommentResource($Comment),
            'user_comments' => ($userComments),
            'message' => "Edit Comment for User Successfully."
        ]);

    }

    public function update(CommentRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $Comment =Comment::findOrFail($id);
       if (!$Comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }
       $Comment->update([
        'comment' => $request->comment,
        'user_id' => $request->user_id,
        ]);

       $Comment->save();
       return response()->json([
        'data' =>new CommentResource($Comment),
        'message' => " Update Comment By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $Comment =Comment::find($id);
    if (!$Comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }

    $Comment->delete($id);
    return response()->json([
        'data' =>new CommentResource($Comment),
        'message' => " Soft Delete Comment By Id Successfully."
    ]);
}
public function showDeleted(){
    $this->authorize('manage_users');
    $Comments=Comment::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>CommentResource::collection($Comments),
        'message' => "Show Deleted Comment Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $Comment=Comment::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Comment By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $Comment=Comment::withTrashed()->where('id',$id)->first();
    if (!$Comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }

    $Comment->forceDelete();
    return response()->json([
        'message' => " Force Delete Comment By Id Successfully."
    ]);
}
}
