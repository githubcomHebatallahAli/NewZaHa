<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\Reset\ForgetPasswordRequest;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = User::where('email',$input)->first();
        $user->notify(new ResetPasswordNotification());
        return response()->json([
            'message' => "Please check your email."
        ]);

    }
}
