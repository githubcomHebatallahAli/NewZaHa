<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialiteController extends Controller
{
public function redirectToGoogle()
    {
        // return response()->json([
            // 'url' => Socialite::driver('google')->redirect()->getTargetUrl(),
            return Socialite::driver('google')->redirect();

        // ]);
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $findUser = User::where('social_id', $user->id)->first();

            if ($findUser) {

                Auth::login($findUser);


                return redirect('https://zaha-script.vercel.app');
            } else {

                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',


                ]);

                Auth::login($newUser);

                return redirect()->away('https://zaha-script.vercel.app');
            }
        } catch (Exception $e) {

            Log::error('Error during Google callback: '.$e->getMessage());


            return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Google.');
        }
    }

}




