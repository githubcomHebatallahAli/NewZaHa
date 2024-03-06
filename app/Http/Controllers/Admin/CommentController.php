<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function showAll()
    {
    $users = User::with('comments')->get();
    $processedUsers = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_comments' => $user->comments->pluck('comment')->toArray(),
        ];
    });
    return response()->json([
        'users' => $processedUsers,
        'message' => "Show All Users With Comments Successfully."
    ], 200);
    }


    public function create(CommentRequest $request)
    {
           $Comment =Comment::create ([
                'comment' => $request->comment,
                'user_id' => $request->user_id,
            ]);
           $Comment->save();
           return response()->json([
            'Comment' =>new CommentResource($Comment),
            'message' => "Comment Created Successfully."
        ], 200);

        }


    public function show(string $id)
    {
    $Comment = Comment::with('user.Comments')->find($id);
    $userComments = $Comment->user->Comments->pluck('comment')->toArray();
    return response()->json([
        'Comment' =>new CommentResource($Comment),
        'user_comments' => ($userComments),
        'message' => "Show Comment for User Successfully."
    ], 200);
    }

    public function edit(string $id)
    {
        $Comment = Comment::with('user.Comments')->find($id);
        $userComments = $Comment->user->Comments->pluck('comment')->toArray();
        return response()->json([
            'Comment' =>new CommentResource($Comment),
            'user_comments' => ($userComments),
            'message' => "Edit Comment for User Successfully."
        ], 200);

    }

    public function update(CommentRequest $request, string $id)
    {
       $Comment =Comment::findOrFail($id);
       $Comment->update([
        'comment' => $request->comment,
        'user_id' => $request->user_id,
        ]);

       $Comment->save();
       return response()->json([
        'Comment' =>new CommentResource($Comment),
        'message' => " Update Comment By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $Comment =Comment::find($id);
    $Comment->delete($id);
    return response()->json([
        'Comment' =>new CommentResource($Comment),
        'message' => " Soft Delete Comment By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $Comments=Comment::onlyTrashed()->with('user')->get();
    return response()->json([
        'Comment' =>CommentResource::collection($Comments),
        'message' => "Show Deleted Comment Successfully."
    ], 200);
}

public function restore(string $id){
    $Comment=Comment::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Comment By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $Comment=Comment::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Comment By Id Successfully."
    ], 200);
}
}
