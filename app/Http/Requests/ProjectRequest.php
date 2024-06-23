<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nameProject'=> 'nullable|string',
            'skills'=> 'nullable|string',
            'description'=> 'nullable|string',
            'price' => 'nullable|integer',
            'saleType'=> 'nullable|string',
            'urlProject'=> 'nullable|string',
            'startingDate' => 'nullable|date',
            'endingDate' => 'nullable|date|after:startingDate',
            // 'team' => 'nullable|string',
            'team_id' => 'required|exists:teams,id',
            'imgProject.*'=>'nullable|image|mimes:jpg,jpeg,png,gif,svg',

        ];
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
