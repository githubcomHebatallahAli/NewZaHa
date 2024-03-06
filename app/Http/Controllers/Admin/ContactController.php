<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    public function index()
    {
         $contacts = Contact::with('user')->get();
            return response()->json([
            'contact' =>ContactResource::collection($contacts),
            'message' => "Show All Contact Successfully."
        ], 200);
    }

    public function store(ContactRequest $request)
    {
           $contact =Contact::create ([
                'phoneNumber' => $request->phoneNumber,
                'message' => $request->message,
                'user_id' => $request->user_id,
            ]);
           $contact->save();
           return response()->json([
            'contact' =>new ContactResource($contacts),
            'message' => "Contact Created Successfully."
        ], 200);
            // if($contact){

            //     return $this->apiResponse(new ContactResource($contact),'The User Contact Created Successfully.',201);
            // }

            // return $this->apiResponse(null,'The User Contact Not Save',400);

        }


    public function show(string $id)
    {
       $contact =Contact::with('user')->find($id);
       return response()->json([
        'contact' =>new ContactResource($contacts),
        'message' => " Show Contact By Id Successfully."
    ], 200);
        // if($contact){
        //     return $this->apiResponse(new ContactResource($contact),'The User Contact Show',201);
        // }
        // return $this->apiResponse(null,'The User Contact Not Found',404);
    }
}
