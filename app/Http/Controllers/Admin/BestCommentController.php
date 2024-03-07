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
        $bestComments = BestComment::with('comment')->get();
        return response()->json([
            'bestComments' => BestCommentResource::collection($bestComments),
            'message' => "Show All Best Comments Successfully."
        ], 200);
    }


    public function create(Request $request)
    {
           $BestComment =BestComment::create ([
                'comment_id' => $request->comment_id,
            ]);
           $BestComment->save();
           return response()->json([
            'bestComment' =>new BestCommentResource($BestComment),
            'message' => "Best Comment Created Successfully."
        ], 200);

        }


    public function show(string $id)
    {
        $bestComments = BestComment::with('comment')->find($id);
        return response()->json([
            'bestComments' =>new BestCommentResource($bestComments),
            'message' => "Show Best Comment By ID Successfully."
        ], 200);
    }

    public function edit(string $id)
    {
        $bestComments = BestComment::with('comment')->find($id);
        return response()->json([
            'bestComments' =>new BestCommentResource($bestComments),
            'message' => "Edit Best Comment By ID Successfully."
        ], 200);

    }

    public function update(Request $request, string $id)
    {
       $BestComment =BestComment::findOrFail($id);
       $BestComment->update([
        'comment_id' => $request->comment_id,
        ]);

       $BestComment->save();
       return response()->json([
        'bestComment' =>new BestCommentResource($BestComment),
        'message' => " Update  Best Comment By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $BestComment =BestComment::find($id);
    $BestComment->delete($id);
    return response()->json([
        'bestComment' =>new BestCommentResource($BestComment),
        'message' => " Soft Delete Best Comment By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $BestComments=BestComment::onlyTrashed()->get();
    return response()->json([
        'bestComment' =>BestCommentResource::collection($BestComments),
        'message' => "Show Deleted Best Comment Successfully."
    ], 200);
}

public function restore(string $id){
    $BestComment=BestComment::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Best Comment By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $BestComment=BestComment::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Best Comment By Id Successfully."
    ], 200);
}
}
