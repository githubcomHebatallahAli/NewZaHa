<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Comment;
use App\Models\BestComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\BestCommentRequest;
use App\Http\Resources\BestCommentResource;

class BestCommentController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $bestComments = BestComment::with('comment')->get();
        return response()->json([
            'data' => BestCommentResource::collection($bestComments),
            'message' => "Show All Best Comments Successfully."
        ]);
    }


    public function create(Request $request)
    {
        $this->authorize('manage_users');
           $BestComment =BestComment::create ([
                'comment_id' => $request->comment_id,
            ]);
           $BestComment->save();
           return response()->json([
            'data' =>new BestCommentResource($BestComment),
            'message' => "Best Comment Created Successfully."
        ]);

        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
        $bestComments = BestComment::with('comment')->find($id);
        if (!$BestComment) {
            return response()->json([
                'message' => "BestComment not found."
            ], 404);
        }
        return response()->json([
            'data' =>new BestCommentResource($bestComments),
            'message' => "Show Best Comment By ID Successfully."
        ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $bestComments = BestComment::with('comment')->find($id);
        if (!$BestComment) {
            return response()->json([
                'message' => "BestComment not found."
            ], 404);
        }
        return response()->json([
            'data' =>new BestCommentResource($bestComments),
            'message' => "Edit Best Comment By ID Successfully."
        ]);

    }

    public function update(Request $request, string $id)
    {
        $this->authorize('manage_users');
       $BestComment =BestComment::findOrFail($id);
       if (!$BestComment) {
        return response()->json([
            'message' => "BestComment not found."
        ], 404);
    }
       $BestComment->update([
        'comment_id' => $request->comment_id,
        ]);

       $BestComment->save();
       return response()->json([
        'data' =>new BestCommentResource($BestComment),
        'message' => " Update  Best Comment By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $BestComment =BestComment::find($id);

    if (!$BestComment) {
     return response()->json([
         'message' => "BestComment not found."
     ], 404);
 }
    $BestComment->delete($id);
    return response()->json([
        'data' =>new BestCommentResource($BestComment),
        'message' => " Soft Delete Best Comment By Id Successfully."
    ]);
}
public function showDeleted(){
    $this->authorize('manage_users');
    $BestComments=BestComment::onlyTrashed()->get();
    return response()->json([
        'data' =>BestCommentResource::collection($BestComments),
        'message' => "Show Deleted Best Comment Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $BestComment=BestComment::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Best Comment By Id Successfully."
    ]);
}
public function forceDelete(string $id){
    $this->authorize('manage_users');
    $BestComment=BestComment::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Best Comment By Id Successfully."
    ]);
}
}
