<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialiteController extends Controller
{
    // public function redirectToGoogle(): JsonResponse
    // {
    //     return response()->json([
    //         'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
    //     ]);
    // }

    // public function handleGoogleCallback(): JsonResponse
    // {

    //     try {
    //         /** @var SocialiteUser $socialiteUser */
    //         $socialiteUser = Socialite::driver('google')->stateless()->user();
    //     } catch (\Exception $e) {
    //         Log::error('خطأ في استرجاع بيانات Google: ' . $e->getMessage());
    //         return response()->json(['error' => 'تم تقديم بيانات اعتماد غير صحيحة.'], 422);
    //     }

    //     /** @var User $user */
    //     $user = User::updateOrCreate(
    //         [
    //             'email' => $socialiteUser->getEmail(),
    //         ],
    //         [
    //             'email_verified_at' => now(),
    //             'name' => $socialiteUser->getName(),
    //             'google_id' => $socialiteUser->getId(),
    //             'avatar' => $socialiteUser->getAvatar(),
    //         ]
    //     );

    //     // إنشاء رمز JWT
    //     $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'user' => $user,
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //     ]);


        // try {
        //     /** @var SocialiteUser $socialiteUser */
        //     $socialiteUser = Socialite::driver('google')->stateless()->user();
        // } catch (ClientException $e) {
        //     return response()->json(['error' => 'Invalid credentials provided.'], 422);
        // }

        // /** @var User $user */
        // $user = User::query()
        //     ->firstOrCreate(
        //         [
        //             'email' => $socialiteUser->getEmail(),
        //         ],
        //         [
        //             'email_verified_at' => now(),
        //             'name' => $socialiteUser->getName(),
        //             'google_id' => $socialiteUser->getId(),
        //             'avatar' => $socialiteUser->getAvatar(),
        //         ]
        //     );

        // return response()->json([
        //     'user' => $user,
        //     'access_token' => $user->createToken('google-token')->plainTextToken,
        //     'token_type' => 'Bearer',
        // ]);





        public function redirectToGoogle()
        {
            return Socialite::driver('google')->stateless()->redirect();
        }

        public function handleGoogleCallback(): JsonResponse
        {
            try {
                $socialiteUser = Socialite::driver('google')->stateless()->user();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid credentials provided.'], 422);
            }

            $user = User::updateOrCreate(
                ['email' => $socialiteUser->getEmail()],
                [
                    'email_verified_at' => now(),
                    'name' => $socialiteUser->getName(),
                    'google_id' => $socialiteUser->getId(),
                    'avatar' => $socialiteUser->getAvatar(),
                ]
            );

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }

