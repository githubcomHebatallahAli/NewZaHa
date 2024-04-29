<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;


class OrderController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');


$usersWithOrders = User::whereHas('orders')->with('orders')->get();

$usersArray = $usersWithOrders->map(function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
        'orders' => $user->orders->map->only([
            'id', 'phoneNumber', 'nameProject', 'price', 'condition', 'description'
        ]),
    ];
})->toArray();


return response()->json([
    'data' => $usersArray,
    'message' => "Show All Users with Orders Successfully."
]);
    }



    public function create(OrderRequest $request)
    {
        $this->authorize('manage_users');
           $Order =Order::create ([
                'phoneNumber' => $request->phoneNumber,
                'nameProject' => $request->nameProject,
                'price' => $request->price,
                'condition' => $request->condition,
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);
            $Order->addMediaFromRequest('url')->toMediaCollection('Orders');
           $Order->save();
           return response()->json([
            'data' =>new OrderResource($Order),
            'message' => "Order Created Successfully."
        ]);

        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
    $Order = Order::with('user.orders')->find($id);
    if (!$Order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }
    return response()->json([
        'data' =>new OrderResource($Order),
        'message' => "Show Order for User Successfully."
    ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $Order = Order::with('user.orders')->find($id);
        if (!$Order) {
            return response()->json([
                'message' => "Order not found."
            ], 404);
        }
        return response()->json([
            'data' =>new OrderResource($Order),
            'message' => "Edit Order for User Successfully."
        ]);

    }

    public function update(OrderRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $Order =Order::findOrFail($id);
       if (!$Order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }
       $Order->update([
        'phoneNumber' => $request->phoneNumber,
        'nameProject' => $request->nameProject,
        'price' => $request->price,
        'condition' => $request->condition,
        'description' => $request->description,
        'user_id' => $request->user_id,
        ]);
        $Order->clearMediaCollection('Orders');

        if ($request->hasFile('url')) {
            $Order->addMediaFromRequest('url')->toMediaCollection('Orders');
        }

       $Order->save();
       return response()->json([
        'data' =>new OrderResource($Order),
        'message' => " Update Order By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $Order =Order::find($id);
    if (!$Order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }

    $Order->delete($id);
    return response()->json([
        'data' =>new OrderResource($Order),
        'message' => " Soft Delete Order By Id Successfully."
    ]);
}

public function showDeleted(){
    $this->authorize('manage_users');
    $Orders=Order::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>OrderResource::collection($Orders),
        'message' => "Show Deleted Order Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $Order=Order::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Order By Id Successfully."
    ]);
}
public function forceDelete(string $id){
    $this->authorize('manage_users');
    $Order=Order::withTrashed()->where('id',$id)->first();
    if (!$Order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }

    if ($Order) {
        $Order->getMedia('Orders')->each(function ($media) {
            $media->delete();
        });

        $Order->forceDelete();
    return response()->json([
        'message' => " Force Delete Order By Id Successfully."
    ]);
}
}
}
