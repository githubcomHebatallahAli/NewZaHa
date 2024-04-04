<?php

namespace App\Http\Controllers\User;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Http\Requests\ChangePasswordRequest;

class ClientUserController extends Controller
{

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = request()->user()->update([
            'password'=>$request->new_password
        ]);
        return response()->json([
            'message' => 'Password changed successfully.'
        ]);
    }


    public function create(ClientRequest $request)
    {
        $this->authorize('create', Client::class);
           $Client =Client::create ([
                'realName' => $request->realName,
                'user_id' => $request->user()->id,
            ]);
            $Client->addMediaFromRequest('photo')->toMediaCollection('Clients');
           $Client->save();
           return response()->json([
            'data' =>new ClientResource($Client),
            'message' => "Client Created Successfully."
        ]);
        }


    public function show(string $id)
    {
       $Client =Client::with('orders')->find($id);
       $this->authorize('show', $Client);
       if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }
       return response()->json([
        'data' =>new ClientResource($Client),
        'message' => " Show Client By Id Successfully."
    ]);
    }

    public function edit(string $id)
    {

       $Client =Client::with('orders')->find($id);
       $this->authorize('edit', $Client);
       if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }
       return response()->json([
        'data' =>new ClientResource($Client),
        'message' => " Edit Client By Id Successfully."
    ]);
    }

    public function update(ClientRequest $request, string $id)
    {

       $Client =Client::findOrFail($id);
       $this->authorize('update', $Client);
       if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }
       $Client->update([
        'realName' => $request->realName,
        'user_id' => $request->user()->id,
        ]);
        $Client->clearMediaCollection('Clients');
        if ($request->hasFile('photo')) {
            $Client->addMedia($request->file('photo'))->toMediaCollection('Clients');
        }

       $Client->save();
       return response()->json([
        'data' =>new ClientResource($Client),
        'message' => " Update Client By Id Successfully."
    ]);
}

public function updatePhoto(Request $request, string $id)
{
    $client = Client::findOrFail($id);
    $this->authorize('update', $client);

    if (!$client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }

    if ($request->hasFile('photo')) {
        $client->clearMediaCollection('Clients');
        $client->addMedia($request->file('photo'))->toMediaCollection('Clients');
        $client->save();

        return response()->json([
            'data' => new ClientResource($client),
            'message' => "Photo updated successfully."
        ]);
    } else {
        return response()->json([
            'message' => "No photo provided for update."
        ], 400);
    }
}



public function forceDelete(string $id){

    $Client=Client::withTrashed()->where('id',$id)->first();
    if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }
    $this->authorize('forceDelete', $Client);
    if ($Client) {
        $Client->getMedia('Clients')->each(function ($media) {
            $media->delete();
        });
        $Client->forceDelete();
    return response()->json([
        'message' => " Force Delete Client By Id Successfully."
    ]);
}
}
}
