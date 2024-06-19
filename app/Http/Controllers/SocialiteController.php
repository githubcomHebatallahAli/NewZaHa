<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;


class SocialiteController extends Controller
{
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

public function handleGoogleCallback(): RedirectResponse
{
    try {
        $user = Socialite::driver('google')->user();
        $findUser = User::where('social_id', $user->id)->first();

        if ($findUser) {

            Auth::login($findUser, true);
        } else {

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id' => $user->id,
                'social_type' => 'google',
                'password' => bcrypt(Str::random(16))
            ]);


            Auth::login($newUser, true);
        }


        return redirect()->intended('https://zaha-script.vercel.app');
    } catch (Exception $e) {

        Log::error('Error during Google callback: ' . $e->getMessage());
        return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Google.');
    }
}

    }




