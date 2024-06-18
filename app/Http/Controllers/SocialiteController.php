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
            'url' => Socialite::driver('google')->redirect()->getTargetUrl(),
        ]);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // الحصول على بيانات المستخدم من Google
            $user = Socialite::driver('google')->user();

            // البحث عن المستخدم في قاعدة البيانات حيث يكون معرّف الاجتماعي هو نفس المعرّف الذي وفرته Google
            $findUser = User::where('social_id', $user->id)->first();

            if ($findUser) {
                // تسجيل دخول المستخدم
                Auth::login($findUser);

                // إعادة توجيه المستخدم إلى صفحة لوحة التحكم
                return redirect('https://zaha-script.vercel.app');
            } else {
                // إنشاء بيانات المستخدم باستخدام بيانات حسابه على Google
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    'password' => bcrypt('my-google'),
                ]);

                Auth::login($newUser);

                return redirect('https://zaha-script.vercel.app');
            }
        } catch (Exception $e) {
            // التعامل مع الخطأ وعرض رسالة مفيدة للمستخدم
            Log::error('Error during Google callback: '.$e->getMessage());
            return redirect('https://zaha-script.vercel.app/user/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Google.');
        }
    }
}




