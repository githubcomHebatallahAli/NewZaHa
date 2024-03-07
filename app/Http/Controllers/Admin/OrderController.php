<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function showAll()
    {
        $users = User::with('orders')->get();
    $processedUsers = [];
    foreach ($users as $user) {
        $phoneNumber = null;
        if ($user->orders->isNotEmpty()) {
            $phoneNumber = $user->orders->first()->phoneNumber;
        }
        $processedUsers[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phoneNumber' => $phoneNumber,
        ];

}    return response()->json([
        'users' => $processedUsers,
        'message' => "Show All Users With Orders Successfully."
    ], 200);
    }


    public function create(OrderRequest $request)
    {
           $Order =Order::create ([
                'phoneNumber' => $request->phoneNumber,
                'nameProject' => $request->nameProject,
                'price' => $request->price,
                'condition' => $request->condition,
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);
           $Order->save();
           return response()->json([
            'Order' =>new OrderResource($Order),
            'message' => "Order Created Successfully."
        ], 200);

        }


    public function show(string $id)
    {
    $Order = Order::with('user.orders')->find($id);
    return response()->json([
        'Order' =>new OrderResource($Order),
        'message' => "Show Order for User Successfully."
    ], 200);



    }

    public function edit(string $id)
    {
        $Order = Order::with('user.orders')->find($id);
        return response()->json([
            'Order' =>new OrderResource($Order),
            'message' => "Edit Order for User Successfully."
        ], 200);

    }

    public function update(OrderRequest $request, string $id)
    {
       $Order =Order::findOrFail($id);
       $Order->update([
        'phoneNumber' => $request->phoneNumber,
        'message' => $request->message,
        'user_id' => $request->user_id,
        ]);

       $Order->save();
       return response()->json([
        'Order' =>new OrderResource($Order),
        'message' => " Update Order By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $Order =Order::find($id);
    $Order->delete($id);
    return response()->json([
        'Order' =>new OrderResource($Order),
        'message' => " Soft Delete Order By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $Orders=Order::onlyTrashed()->with('user')->get();
    return response()->json([
        'Order' =>OrderResource::collection($Orders),
        'message' => "Show Deleted Order Successfully."
    ], 200);
}

public function restore(string $id){
    $Order=Order::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Order By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $Order=Order::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Order By Id Successfully."
    ], 200);
}
}
