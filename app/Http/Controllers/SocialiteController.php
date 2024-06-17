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
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
        // return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // الحصول على بيانات المستخدم من Google
            $user = Socialite::driver('google')->stateless()->user();

            // البحث عن المستخدم في قاعدة البيانات بواسطة المعرف الاجتماعي
            $findUser = User::where('social_id', $user->id)->first();

            if ($findUser) {
                // إذا تم العثور على المستخدم، تسجيل دخوله
                Auth::login($findUser);

                // إعادة توجيه المستخدم إلى الواجهة الأمامية بعد تسجيل الدخول
                return redirect('https://zaha-script.vercel.app');
            } else {
                // إذا لم يتم العثور على المستخدم، إنشاء حساب جديد
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',  // تسجيل الدخول بواسطة Google
                    // لا توجد حاجة لكلمة مرور لأنها لن تُستخدم لتسجيل الدخول
                ]);

                Auth::login($newUser);

                // إعادة توجيه المستخدم إلى الواجهة الأمامية بعد تسجيل الدخول
                return redirect('https://zaha-script.vercel.app');
            }
        } catch (Exception $e) {
            // التعامل مع الخطأ وعرض رسالة مفيدة للمستخدم
            Log::error('Error during Google callback: '.$e->getMessage());
            return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Google.');
        }
    }

}


