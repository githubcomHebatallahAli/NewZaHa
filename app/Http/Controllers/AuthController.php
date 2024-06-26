<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\RegisterResource;
use App\Notifications\EmailVerificationNotification;


class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','verify']]);
    }

    public function login(LoginRequest $request){
    	$validator = Validator::make($request->all(),$request->rules()

        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('api')->attempt($validator->validated())) {
            return response()->json(['message' => 'Invalid Data'], 422);
        }
        $user = auth()->guard('api')->user();

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Email not verified. Please verify it.'
            ], 403);
        }
        return $this->createNewToken($token);


    }

    public function register(RegisterRequest $request) {
        $validator = Validator::make($request->all(), $request->rules()

        );
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
                $user->notify(new EmailVerificationNotification());
        return response()->json([
            'message' => 'Registration successful! Please check your email for verification.',
            'user' =>new RegisterResource($user)
        ], 201);
    }


    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(["data"=>auth()->refresh()
    ]);
    }

    public function userProfile() {
        return response()->json(["data"=>auth()->user()
    ]);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
