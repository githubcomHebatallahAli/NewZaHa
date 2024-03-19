<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Contact;
use App\Mail\NewContactMail;
use Illuminate\Http\Request;
use App\Mail\ContactUpdatedMail;
use App\Mail\ContactWelcomeMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Notifications\NewContactNotification;
use App\Notifications\ContactUpdatedNotification;

class ContactUserController extends Controller
{

    public function create(ContactRequest $request)
    {
        $this->authorize('create', Contact::class);
           $contact =Contact::create ([
                'phoneNumber' => $request->phoneNumber,
                'message' => $request->message,
                'user_id' => $request->user()->id,
            ]);

            $contact->user->notify(new NewContactNotification($contact));

            $admins = User::where('isAdmin', 1)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewContactMail($contact));
            }
            Mail::to($contact->user->email)->send(new ContactWelcomeMail($contact));

           $contact->save();
           return response()->json([
            'data' =>new ContactResource($contact),
            'message' => "Contact Created Successfully."
        ]);
        }


    public function show(string $id)
    {
    $contact = Contact::with('user')->find($id);
    $this->authorize('show', $contact);
    if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }
    return response()->json([
        'data' =>new ContactResource($contact),
        'message' => "Show Contact for User Successfully."
    ]);
    }

    public function edit(string $id)
    {
        $contact = Contact::with('user')->find($id);
        $this->authorize('edit', $contact);
        if (!$contact) {
            return response()->json([
                'message' => "Contact not found."
            ], 404);
        }
        return response()->json([
            'data' =>new ContactResource($contact),
            'message' => "Edit Contact for User Successfully."
        ]);
    }

    public function update(ContactRequest $request, string $id)
    {
       $contact =Contact::findOrFail($id);
       $this->authorize('update', $contact);
       if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }
       $contact->update([
        'phoneNumber' => $request->phoneNumber,
        'message' => $request->message,
        'user_id' => $request->user()->id,
        ]);
        $contact->user->notify(new ContactUpdatedNotification($contact));

        $admins = User::where('isAdmin', 1)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ContactUpdatedMail($contact));
        }
        Mail::to($contact->user->email)->send(new ContactWelcomeMail($contact));
       $contact->save();
       return response()->json([
        'data' =>new ContactResource($contact),
        'message' => " Update Contact By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $contact = Contact::withTrashed()->where('id', $id)->first();
    if (!$contact) {
        return response()->json([
            'message' => "Contact not found."
        ], 404);
    }
    $this->authorize('forceDelete', $contact);
    $contact->forceDelete();
    return response()->json([
        'message' => " Force Delete Contact By Id Successfully."
    ]);
}
}
