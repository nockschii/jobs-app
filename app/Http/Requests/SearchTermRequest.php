<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class SearchTermRequest extends FormRequest
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
            'searchterm' => 'required|string|max:255|min:1',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'searchterm.required' => 'Search term is required',
            'searchterm.string' => 'Search term must be a string',
            'searchterm.max' => 'Search term cannot exceed 255 characters',
            'searchterm.min' => 'Search term cannot be empty',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        // Für API routes (die mit /api/ beginnen) JSON Response
        if ($this->is('api/*')) {
            throw new HttpResponseException(
                response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()
                ], 400)
            );
        }

        // Für andere Routes den Standard Laravel Behavior
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
