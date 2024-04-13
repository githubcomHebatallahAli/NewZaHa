<?php

namespace App\Http\Requests\Auth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|max:255',
        ];
    }

    public function authenticate()
    {
        // استخدم Auth::attempt() للتحقق من بيانات تسجيل الدخول
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            // إذا لم ينجح التحقق، قم بإرجاع خطأ أو رسالة مناسبة
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }


}
