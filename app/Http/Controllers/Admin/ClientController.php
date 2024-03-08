<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    public function showAll()
    {
         $Clients = Client::with('user')->get();

            return response()->json([
            'Client' =>ClientResource::collection($Clients),
            'message' => "Show All Clients Successfully."
        ], 200);
    }

    public function create(ClientRequest $request)
    {
           $Client =Client::create ([
                'phoneNumber' => $request->phoneNumber,
                'projectName' => $request->projectName,
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);
            $Client->addMediaFromRequest('photo')->toMediaCollection('Clients');
           $Client->save();
           return response()->json([
            'Client' =>new ClientResource($Client),
            'message' => "Client Created Successfully."
        ], 200);


        }


    public function show(string $id)
    {
       $Client =Client::with('user')->find($id);
       return response()->json([
        'Client' =>new ClientResource($Client),
        'message' => " Show Client By Id Successfully."
    ], 200);

    }

    public function edit(string $id)
    {
       $Client =Client::with('user')->find($id);
       return response()->json([
        'Client' =>new ClientResource($Client),
        'message' => " Edit Client By Id Successfully."
    ], 200);

    }

    public function update(ClientRequest $request, string $id)
    {
       $Client =Client::findOrFail($id);
       $Client->update([
        'phoneNumber' => $request->phoneNumber,
        'projectName' => $request->projectName,
        'description' => $request->description,
        'user_id' => $request->user_id,
        ]);
        $Client->clearMediaCollection('Clients');
        if ($request->hasFile('photo')) {
            $Client->addMedia($request->file('photo'))->toMediaCollection('Clients');
        }

       $Client->save();
       return response()->json([
        'Client' =>new ClientResource($Client),
        'message' => " Update Client By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $Client =Client::find($id);
    $Client->delete($id);
    return response()->json([
        'Client' =>new ClientResource($Client),
        'message' => " Soft Delete Client By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $Clients=Client::onlyTrashed()->with('user')->get();
    return response()->json([
        'Client' =>ClientResource::collection($Clients),
        'message' => "Show Deleted Client Successfully."
    ], 200);
}

public function restore(string $id){
    $Client=Client::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Client By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $Client=Client::withTrashed()->where('id',$id)->first();
    if ($Client) {
        $Client->getMedia('Clients')->each(function ($media) {
            $media->delete();
        });
        $Client->forceDelete();
    return response()->json([
        'message' => " Force Delete Client By Id Successfully."
    ], 200);
}
}
}
