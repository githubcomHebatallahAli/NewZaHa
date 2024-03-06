<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Reset\ResetPasswordRequest;
use App\Http\Requests\Reset\SendResetEmailRequest;
// use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    // use ResetsPasswords;
    public function sendResetLinkEmail(SendResetEmailRequest $request){
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
        if($status){
         return response()->json(['message' => __($status)], 200);
        }else {
         return response()->json(['error' => __($status)], 400);
     }

    }
    // $user = User::where('email', $request->email)->first();

    // if (!$user) {
    //     return response()->json(['error' => 'User not found'], 404);
    // }

    // $token = $user->createToken('Password Reset Token')->plainTextToken;

    // // Generate the password reset URL
    // $resetUrl = url('reset/'.$token);

    // // Here you can send the $resetUrl along with other data in the response
    // return response()->json(['reset_url' => $resetUrl, 'message' => 'Password reset link generated successfully'], 200);
}

     public function reset(ResetPasswordRequest $request){
        $status = Password::reset(
            $request->only('email', 'password', 'confirm_password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
         return response()->json(['message' => __($status)]);

     }
}

