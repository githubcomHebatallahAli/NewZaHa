<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Reset\ResetPasswordRequest;
use App\Http\Requests\Reset\SendResetEmailRequest;


class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(SendResetEmailRequest $request){
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
        if($status){
         return response()->json
         (['message' => __($status)], 200);
        }else {
         return response()->json
         (['error' => __($status)], 400);
     }

    }

}

    public function reset(ResetPasswordRequest $request){
        $user = User::where('email', $request->email)->first();

        if ($user) {

            $user->forceFill([
                'password' => bcrypt($request->password),
            ])->save();


            return response()->json([
                'message' => __('Password has been reset successfully.')
            ]);
        } else {

            return response()->json([
                'message' => __('User not found.')]
                , 404);
        }
    }

}

