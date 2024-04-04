<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
         $Clients = Client::with('user')->get();


            return response()->json([
            'data' =>ClientResource::collection($Clients),
            'message' => "Show All Clients Successfully."
        ]);
    }

    public function create(ClientRequest $request)
    {
        $this->authorize('manage_users');
           $Client =Client::create ([
                'realName' => $request->realName,
                'user_id' => $request->user_id,
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
        $this->authorize('manage_users');
       $Client =Client::with('order')->find($id);
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
        $this->authorize('manage_users');
       $Client =Client::with('order')->find($id);
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
        $this->authorize('manage_users');
       $Client =Client::findOrFail($id);
       if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }
       $Client->update([
        'realName' => $request->realName,
        'user_id' => $request->user_id,
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

public function destroy(string $id){
    $this->authorize('manage_users');
    $Client =Client::find($id);
    if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }

    $Client->delete($id);
    return response()->json([
        'data' =>new ClientResource($Client),
        'message' => " Soft Delete Client By Id Successfully."
    ]);
}

public function showDeleted(){
    $this->authorize('manage_users');
    $Clients=Client::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>ClientResource::collection($Clients),
        'message' => "Show Deleted Client Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $Client=Client::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Client By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $Client=Client::withTrashed()->where('id',$id)->first();
    if (!$Client) {
        return response()->json([
            'message' => "Client not found."
        ], 404);
    }

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
