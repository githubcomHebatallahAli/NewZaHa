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
    // return Socialite::driver('google')->redirect();
    return Socialite::driver('google')->redirect()->getTargetUrl();
}

// public function handleGoogleCallback(): RedirectResponse
// {
//     try {
//         $user = Socialite::driver('google')->user();
//         $findUser = User::where('social_id', $user->id)->first();

//         if ($findUser) {

//             Auth::login($findUser, true);
//         } else {

//             $newUser = User::create([
//                 'name' => $user->name,
//                 'email' => $user->email,
//                 'social_id' => $user->id,
//                 'social_type' => 'google',
//                 'password' => bcrypt(Str::random(16))
//             ]);


//             Auth::login($newUser, true);
//         }


//         return redirect()->intended('https://zaha-script.vercel.app');
//     } catch (Exception $e) {

//         Log::error('Error during Google callback: ' . $e->getMessage());
//         return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Google.');
//     }
// }


public function handleGoogleCallback(): RedirectResponse
{

    try {
        // Retrieve user data from Google
        $user = Socialite::driver('google')->user();

        // Check if the user already exists in your database
        $findUser = User::where('social_id', $user->id)->first();

        if ($findUser) {
            // If user exists, update or create JWT token
            $token = $findUser->createToken('authToken')->plainTextToken;
        } else {
            // If user does not exist, create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id' => $user->id,
                'social_type' => 'google',
                'password' => bcrypt(Str::random(16))
            ]);

            // Create JWT token for the new user
            $token = $newUser->createToken('authToken')->plainTextToken;
            $findUser = $newUser; // Assign the new user to $findUser
        }

        // Prepare data to return in the query parameters
        $queryParams = http_build_query([
            'token' => $token,
            'user' => [
                'id' => $findUser->id,
                'name' => $findUser->name,
                'email' => $findUser->email,
                'email_verified_at' => $findUser->email_verified_at,
                'isAdmin' => $findUser->isAdmin,
                'deleted_at' => $findUser->deleted_at,
                'created_at' => $findUser->created_at,
                'updated_at' => $findUser->updated_at,
                'social_id' => $findUser->social_id,
                'social_type' => $findUser->social_type
            ]
        ]);

        // Redirect to your frontend with the JWT token and user data as query parameters
        return redirect()->intended('https://zaha-script.vercel.app/login/success?' . $queryParams);
    } catch (\Exception $e) {
        // Log any errors that occur during the callback process
        Log::error('Error during Google callback: ' . $e->getMessage());

        // Redirect to your frontend login page with an error message
        return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'An error occurred during login with Google.');
    }

    }


}

