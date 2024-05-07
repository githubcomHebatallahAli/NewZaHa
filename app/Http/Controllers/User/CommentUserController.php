<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Comment;
use App\Mail\NewCommentMail;
use App\Mail\CommentUpdatedMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CommentRequest;
use App\Notifications\CommentUpdated;
use App\Http\Resources\CommentResource;
use App\Http\Resources\UserCommentResource;
use App\Notifications\NewCommentNotification;

class CommentUserController extends Controller
{
    public function create(CommentRequest $request)
    {
               $this->authorize('create', Comment::class);
            $comment = Comment::create([
                'comment' => $request->comment,
                'user_id' => $request->user()->id,
            ]);

            $comment->user->notify(new NewCommentNotification($comment));

            $admins = User::where('isAdmin', 1)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewCommentMail($comment));
            }

            return response()->json([
                'data' => new CommentResource($comment),
                'message' => "Comment Created Successfully."
            ]);
    }


    public function show(string $id)
    {

    $Comment = Comment::with('user')->find($id);
    $this->authorize('show', $Comment);
    if (!$Comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }

    return response()->json([
        'data' =>new  UserCommentResource($Comment),
        'message' => "Show Comment for User Successfully."
    ]);
    }

    public function edit(string $id)
    {
        $Comment = Comment::with('user')->find($id);
        $this->authorize('edit', $Comment);
        if (!$Comment) {
            return response()->json([
                'message' => "Comment not found."
            ], 404);
        }
        return response()->json([
            'data' =>new  UserCommentResource($Comment),
            'message' => "Edit Comment for User Successfully."
        ]);

    }

    public function update(CommentRequest $request, string $id)
    {
    $comment = Comment::findOrFail($id);
    $this->authorize('update', $comment);
    if (!$comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }

    $comment->update([
        'comment' => $request->comment,
        'user_id' => $request->user()->id,
    ]);
    $comment->user->notify(new CommentUpdated($comment));


    $admins = User::where('isAdmin', 1)->get();
    foreach ($admins as $admin) {
        Mail::to($admin->email)->send(new CommentUpdatedMail($comment));
    }

    return response()->json([
        'data' => new UserCommentResource($comment),
        'message' => "Comment updated successfully."
    ]);
}


public function forceDelete(string $id){

    $comment = Comment::withTrashed()->where('id', $id)->first();
    if (!$comment) {
        return response()->json([
            'message' => "Comment not found."
        ], 404);
    }

    $this->authorize('forceDelete', $comment);
    $comment->forceDelete();

    return response()->json([
        'message' => "Force Delete Comment By Id Successfully."
    ]);
}

}
