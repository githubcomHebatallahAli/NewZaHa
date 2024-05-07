<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
//     public function showAll()
//     {
//         $this->authorize('manage_users');
//         $users = User::with('contactUs')->get();
//     $processedUsers = [];
//     foreach ($users as $user) {
//         $phoneNumber = null;
//         $userMessages = [];
//         if ($user->contactUs->isNotEmpty()) {
//             $phoneNumber = $user->contactUs->first()->phoneNumber;
//             $userMessages = $user->contactUs->pluck('message')->toArray();
//         }
//         $processedUsers[] = [
//             'id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//             'phoneNumber' => $phoneNumber,
//             'user_messages' => $userMessages,
//         ];

// }    return response()->json([
//         'data' => $processedUsers,
//         'message' => "Show All Users With Messages Successfully."
//     ]);
//     }

    public function showAll()
    {
        $this->authorize('manage_users');

        $usersWithContacts = User::whereHas('contactUs')->get();

        $usersArray = $usersWithContacts->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'contacts' => $user->contactUs->map(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'phoneNumber' => $contact->phoneNumber,
                        'message' => $contact->message
                            ];
                        }),
                    ];
        })->toArray();

        return response()->json([
            'data' => $usersArray,
            'message' => "Show All Users With Messages Of Contact Us Successfully."
        ]);
    }


    public function create(ContactRequest $request)
    {
        $this->authorize('manage_users');
           $contact =Contact::create ([
                'phoneNumber' => $request->phoneNumber,
                'message' => $request->message,
                'user_id' => $request->user_id,
            ]);
           $contact->save();
           return response()->json([
            'data' =>new ContactResource($contact),
            'message' => "Contact Created Successfully."
        ]);
        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
    $contact = Contact::with('user.contactUs')->find($id);
    if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }
    $userContacts = $contact->user->contactUs->pluck('message')->toArray();
    return response()->json([
        'data' =>new ContactResource($contact),
        'user_messages' => ($userContacts),
        'message' => "Show Contact for User Successfully."
    ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $contact = Contact::with('user.contactUs')->find($id);
        if (!$contact) {
            return response()->json([
                'message' => "Contact not found."
            ], 404);
        }
        $userContacts = $contact->user->contactUs->pluck('message')->toArray();
        return response()->json([
            'data' =>new ContactResource($contact),
            'user_messages' => ($userContacts),
            'message' => "Edit Contact for User Successfully."
        ]);
    }

    public function update(ContactRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $contact =Contact::findOrFail($id);
       if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }
       $contact->update([
        'phoneNumber' => $request->phoneNumber,
        'message' => $request->message,
        'user_id' => $request->user_id,
        ]);

       $contact->save();
       return response()->json([
        'data' =>new ContactResource($contact),
        'message' => " Update Contact By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $contact =Contact::find($id);
    if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }

    $contact->delete($id);
    return response()->json([
        'data' =>new ContactResource($contact),
        'message' => " Soft Delete Contact By Id Successfully."
    ]);
}

public function showDeleted(){
    $this->authorize('manage_users');
    $contacts=Contact::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>ContactResource::collection($contacts),
        'message' => "Show Deleted Contact Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $contact=Contact::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Contact By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $contact=Contact::withTrashed()->where('id',$id)->first();
    if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }

    $contact->forceDelete();
    return response()->json([
        'message' => " Force Delete Contact By Id Successfully."
    ]);
}
}
