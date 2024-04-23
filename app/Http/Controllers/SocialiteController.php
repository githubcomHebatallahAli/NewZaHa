<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()

    {

        // try {
        //     $user = Socialite::driver('google')->user();
        //     $finduser = User::where('social_id', $user->id)->first();

        //     if($finduser){

        //         Auth::login($finduser);
        //         // return redirect()->intended('dashboard');
        //         return response()->json($finduser);
        //     }else{

        //         $newUser = User::updateOrCreate(['email' => $user->email],[

        //                 'name' => $user->name,

        //                 'social_id'=> $user->id,
        //                 'social_type'=> 'google',

        //                 'password' => Hash::make('my-google')

        //             ]);
        //         Auth::login($newUser);
        //         // return redirect()->intended('dashboard');
        //         return response()->json($finduser);
        //     }

        // } catch (Exception $e) {

        //     dd($e->getMessage());

        // }

        try {
            $user = Socialite::driver('google')->user();
            $findUser = User::where('social_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return response()->json($findUser);
            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    'password' => Hash::make('my-google')
                ]);
                Auth::login($newUser);
                return response()->json($newUser);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }
}
