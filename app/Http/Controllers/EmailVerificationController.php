<?php

namespace App\Http\Controllers;

use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Notifications\EmailVerificationNotification;
use App\Models\User;


class EmailVerificationController extends Controller
{
private $otp;
public function __construct(){
    $this->otp = new Otp;
}

public function resend(Request $request, $id)
{

    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified'], 400);
    }
    $user->notify(new EmailVerificationNotification());


    return response()->json([
    'message' => "The email resend again."
]);
}



public function verify(EmailVerificationRequest $request)
{
    $otp2 =$this->otp->validate($request->email,$request->otp);
    if (!$otp2->status){
        return response()->json(['error' => $otp2],401);
    }
    $user = User::where('email',$request->email)->first();
    $user->update(['email_verified_at' => now()]);
    return response()->json([
        'message' => "The email verified successfully."
    ]);
}

}
