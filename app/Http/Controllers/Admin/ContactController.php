<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    public function showAll()
    {
        $users = User::with('contactUs')->get();

        $processedUsers = [];
        foreach ($users as $user) {
            $processedUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contactUs' => $user->contactUs->pluck('message')->toArray(),
            ];
        }

        return response()->json([
            'users' => $processedUsers,
            'message' => "Show All Users Successfully."
        ], 200);
}



    public function create(ContactRequest $request)
    {
           $contact =Contact::create ([
                'phoneNumber' => $request->phoneNumber,
                'message' => $request->message,
                'user_id' => $request->user_id,
            ]);
           $contact->save();
           return response()->json([
            'contact' =>new ContactResource($contact),
            'message' => "Contact Created Successfully."
        ], 200);

        }


    public function show(string $id)
    {
    $contact = Contact::with('user.contactUs')->find($id);
    $userContacts = $contact->user->contactUs->pluck('message')->toArray();
    return response()->json([
        'contact' =>new ContactResource($contact),
        'user_messages' => ($userContacts),
        'message' => "Show Contact for User Successfully."
    ], 200);

    }

    public function edit(string $id)
    {
        $contact = Contact::with('user.contactUs')->find($id);
        $userContacts = $contact->user->contactUs->pluck('message')->toArray();
        return response()->json([
            'contact' =>new ContactResource($contact),
            'user_messages' => ($userContacts),
            'message' => "Edit Contact for User Successfully."
        ], 200);

    }

    public function update(ContactRequest $request, string $id)
    {
       $contact =Contact::findOrFail($id);
       $contact->update([
        'phone' => $request->phone,
        'message' => $request->message,
        'user_id' => $request->user_id,
        ]);

       $contact->save();
       return response()->json([
        'contact' =>new ContactResource($contact),
        'message' => " Update Contact By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $contact =Contact::find($id);
    $contact->delete($id);
    return response()->json([
        'contact' =>new ContactResource($contact),
        'message' => " Soft Delete Contact By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $contacts=Contact::onlyTrashed()->with('user')->get();
    return response()->json([
        'contact' =>ContactResource::collection($contacts),
        'message' => "Show Deleted Contact Successfully."
    ], 200);
}

public function restore(string $id){
    $contact=Contact::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Contact By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $contact=Contact::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Contact By Id Successfully."
    ], 200);
}
}
