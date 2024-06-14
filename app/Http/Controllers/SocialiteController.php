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
        //     'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        // ]);
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        // try {
        //     $user = Socialite::driver('google')->stateless()->user();

        //     // منطق التعامل مع المستخدم المصادق عليه، على سبيل المثال، العثور على أو إنشاء مستخدم في قاعدة بياناتك

        //     return response()->json([
        //         'user' => $user,
        //         'access_token' => $user->token,
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json(['error' => 'Authentication failed'], 401);
        // }


            // try {
            //         $socialiteUser = Socialite::driver('google')->stateless()->user();
            //     } catch (\Exception $e) {
            //         return response()->json(['error' => 'Invalid credentials provided.'], 422);
            //     }

            //     $user = User::updateOrCreate(
            //         ['email' => $socialiteUser->getEmail()],
            //         [
            //             'email_verified_at' => now(),
            //             'name' => $socialiteUser->getName(),
            //             'google_id' => $socialiteUser->getId(),
            //             'avatar' => $socialiteUser->getAvatar(),
            //         ]
            //     );

            //     $token = JWTAuth::fromUser($user);

            //     return response()->json([
            //         'user' => $user,
            //         'access_token' => $token,
            //         'token_type' => 'Bearer',
            //     ]);


    //   try {
    //         // التحقق مما إذا كان الطلب يحتوي على المعلمة 'code'
    //         if (!$request->has('code')) {
    //             throw new Exception('المعلمة المطلوبة مفقودة: code');
    //         }

    //         // استرجاع الكود من الطلب
    //         $code = $request->input('code');

    //         // يمكنك الآن إعادة الكود كاستجابة JSON ليتمكن الجزء الأمامي من استخدامه
    //         return response()->json([
    //             'code' => $code,
    //         ]);
    //     } catch (Exception $e) {
    //         // تسجيل الخطأ لأغراض التصحيح
    //         \Log::error('خطأ في استدعاء Google OAuth: ' . $e->getMessage());

    //         return response()->json(['error' => 'فشل التحقق: ' . $e->getMessage()], 401);
    //     }
    //     }


    try {
        // get user data from Google
        $user = Socialite::driver('google')->user();

        // find user in the database where the social id is the same with the id provided by Google
        $finduser = User::where('social_id', $user->id)->first();

        if ($finduser)  // if user found then do this
        {
            // Log the user in
            Auth::login($finduser);

            // redirect user to dashboard page
            return redirect('/dashboard');
        }
        else
        {
            // if user not found then this is the first time he/she try to login with Google account
            // create user data with their Google account data
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id' => $user->id,
                'social_type' => 'google',  // the social login is using google
                'password' => bcrypt('my-google'),  // fill password by whatever pattern you choose
            ]);

            Auth::login($newUser);

            return redirect('/dashboard');
        }

    }
    catch (Exception $e)
    {
        dd($e->getMessage());
    }
    }

    }
