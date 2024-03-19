<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Order;
use App\Mail\NewOrderMail;
use Illuminate\Http\Request;
use App\Mail\OrderWelcomeMail;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderUserResource;
use App\Notifications\NewOrderNotification;

class OrderUserController extends Controller
{
    public function create(OrderRequest $request)
    {
        $this->authorize('create', Order::class);
           $order =Order::create ([
                'phoneNumber' => $request->phoneNumber,
                'user_id' => $request->user()->id,
            ]);
            $order->user->notify(new NewOrderNotification($order));

            $admins = User::where('isAdmin', 1)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewOrderMail($order));
            }
            Mail::to($order->user->email)->send(new OrderWelcomeMail($order));
           $order->save();
           return response()->json([
            'data' =>new OrderUserResource($order),
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
        'data' =>new OrderUserResource($Order),
        'message' => "Show Order for User Successfully."
    ]);
    }

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
            'data' =>new OrderUserResource($Order),
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
        'user_id' => $request->user()->id,
        ]);
        $order->user->notify(new NewOrderNotification($order));

        $admins = User::where('isAdmin', 1)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewOrderMail($order));
        }
        Mail::to($order->user->email)->send(new OrderWelcomeMail($order));

       $Order->save();
       return response()->json([
        'data' =>new  OrderUserResource($Order),
        'message' => " Update Order By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    // $order=order::withTrashed()->where('id',$id);
    $order = Order::withTrashed()->where('id', $id)->first();
    if (!$order) {
        return response()->json([
            'message' => "Order not found."
        ], 404);
    }
    $this->authorize('forceDelete', $order);
    $order->forceDelete();
    return response()->json([
        'message' => " Force Delete order By Id Successfully."
    ]);
}
}
