<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ResetPasswordRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
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
