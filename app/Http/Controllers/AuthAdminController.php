<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Auth\RegisterResource;
use App\Notifications\EmailVerificationNotification;

class AuthAdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api',
         ['except' => ['login',"register",'verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['message' => 'Invalid data'], 422);

            $user = auth()->guard('api')->user();

            if (is_null($user->email_verified_at)) {
                return response()->json([
                    'message' => 'Email not verified. Please verify it.'
                ], 403);
            }
        }

        return $this->createNewToken($token);
    }

    /**
     * Register an Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // Register an Admin.
    public function register(AdminRequest $request)
    {

        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $admin = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password), ]

        ));
        $admin->isAdmin = 1;

        $admin->save();
        $admin->notify(new EmailVerificationNotification());

        return response()->json([
            'message' => 'Admin Registration successful! Please check your email for verification.',
            'user' =>new RegisterResource($admin)
        ]);
    }


    /**
     * Log the admin out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Admin successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(["data" => auth()->user()]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
