<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialiteController extends Controller
{
    public function redirectToGoogle(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
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


      try {
            // التحقق مما إذا كان الطلب يحتوي على المعلمة 'code'
            if (!$request->has('code')) {
                throw new Exception('المعلمة المطلوبة مفقودة: code');
            }

            // استرجاع الكود من الطلب
            $code = $request->input('code');

            // يمكنك الآن إعادة الكود كاستجابة JSON ليتمكن الجزء الأمامي من استخدامه
            return response()->json([
                'code' => $code,
            ]);
        } catch (Exception $e) {
            // تسجيل الخطأ لأغراض التصحيح
            \Log::error('خطأ في استدعاء Google OAuth: ' . $e->getMessage());

            return response()->json(['error' => 'فشل التحقق: ' . $e->getMessage()], 401);
        }
        }
    }

