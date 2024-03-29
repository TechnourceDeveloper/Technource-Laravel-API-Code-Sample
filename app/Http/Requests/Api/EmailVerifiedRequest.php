<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class EmailVerifiedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore(request()->user()->user_id, 'user_id')->whereNull('deleted_at')]
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->first(); // Here is your array of errors

        $response = response()->json([
            'message' => $errors, 'response' => 422
        ], 422);
        throw new HttpResponseException($response);
    }
}
