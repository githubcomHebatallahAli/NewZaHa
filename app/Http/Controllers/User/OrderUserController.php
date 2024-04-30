<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Mail\NewOrderMail;
use App\Mail\OrderWelcomeMail;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderUpdatedNotification;

class OrderUserController extends Controller
{


public function showAll()
{
    $user = auth()->user();
    $userWithOrders = User::where('id', $user->id)->with(['orders', 'orders.media' => function ($query) {
        $query->select('model_id', 'file_name')->where('collection_name', 'Orders');
    }])->first();

    return response()->json([
        'data' => [
            'id' => $userWithOrders->id,
            'name' => $userWithOrders->name,
            'email' => $userWithOrders->email,
            'password' => $userWithOrders->password,
            'orders' => $userWithOrders->orders->map(function ($order) {
                $media = $order->media->first();
                $mediaUrl = $media ? url(Storage::url($media->file_name)) : null;

                return [
                    'id' => $order->id,
                    'phoneNumber' => $order->phoneNumber,
                    'nameProject' => $order->nameProject,
                    'price' => $order->price,
                    'condition' => $order->condition,
                    'description' => $order->description,
                    'media' => [
                        ['url' => $mediaUrl],
                    ],
                ];
            }),
        ],
        'message' => "Show User with Orders Successfully."
    ]);
}


    public function create(OrderRequest $request)
    {
        $this->authorize('create', Order::class);
           $order =Order::create ([
                'phoneNumber' => $request->phoneNumber,
                'nameProject' => $request->nameProject,
                'price' => $request->price,
                'condition' => $request->condition,
                'description' => $request->description,
                'user_id' => $request->user()->id,
            ]);
            if ($request->hasFile('file')) {
                $order->addMediaFromRequest('file')->toMediaCollection('Orders');
            }

            $admins = User::where('isAdmin', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
                // Mail::to($admin->email)->send(new NewOrderMail($order));
            }
                // Mail::to($order->user->email)->send(new OrderWelcomeMail($order));
           $order->save();
           return response()->json([
            'data' =>new OrderResource($order),
            'message' => "Order Created Successfully."
        ]);

        }


    public function show(string $id)
    {
    $Order = Order::with('user.orders')->find($id);
    $this->authorize('show', $Order);
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

//     public function show(string $id)
// {
//     $user = User::find($id);
//         // Authorize the user
//     // $this->authorize('show', $user);
//     if (!$user) {
//         return response()->json([
//             'message' => "User not found."
//         ], 404);
//     }

//     $orders = $user->orders;

//     return response()->json([
//         'data' => OrderResource::collection($orders),
//         'message' => "Show Orders for User Successfully."
//     ]);
// }


    public function edit(string $id)
    {
        $Order = Order::with('user.orders')->find($id);
        $this->authorize('edit', $Order);
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
       $Order =Order::findOrFail($id);
       $this->authorize('update', $Order);
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
        'user_id' => $request->user()->id,
        ]);
        $Order->clearMediaCollection('Orders');

        if ($request->hasFile('file')) {
            $Order->addMediaFromRequest('file')->toMediaCollection('Orders');
        }

        $admins = User::where('isAdmin', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderUpdatedNotification($Order));
            Mail::to($admin->email)->send(new NewOrderMail($Order));

        }
        Mail::to($Order->user->email)->send(new OrderWelcomeMail($Order));

       $Order->save();
       return response()->json([
        'data' =>new  OrderResource($Order),
        'message' => " Update Order By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $order = Order::withTrashed()->where('id', $id)->first();
    if (!$order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }
    $this->authorize('forceDelete', $order);
    if ($order) {
        $order->getMedia('Orders')->each(function ($media) {
            $media->delete();
        });

    $order->forceDelete();
    return response()->json([
        'message' => " Force Delete order By Id Successfully."
    ]);
}
}
}
