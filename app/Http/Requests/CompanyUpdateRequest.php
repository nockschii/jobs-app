<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:255',
            'description' => 'sometimes|string',
            'address' => 'sometimes|string|max:200',
            'city' => 'sometimes|string|max:60',
            'country' => 'sometimes|string|max:60',
            'postal_code' => 'sometimes|string|max:20',
            'phone' => 'sometimes|string|max:20',
            'website' => 'sometimes|url|max:255',
            'linkedin_url' => 'sometimes|url|max:255',
            'industry' => 'sometimes|string|max:50',
            'logo' => 'sometimes|string|max:255',
            '*' => function ($attribute, $value, $fail) {
                if (empty(array_filter($this->all()))) {
                    $fail('At least one field must be provided for update.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The name may not be greater than 100 characters.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'address.max' => 'The address may not be greater than 200 characters.',
            'city.max' => 'The city may not be greater than 60 characters.',
            'country.max' => 'The country may not be greater than 60 characters.',
            'postal_code.max' => 'The postal code may not be greater than 20 characters.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'website.max' => 'The website URL may not be greater than 255 characters.',
            'linkedin_url.max' => 'The LinkedIn URL may not be greater than 255 characters.',
            'industry.max' => 'The industry may not be greater than 50 characters.',
            'logo.max' => 'The logo URL may not be greater than 255 characters.',
        ];
    }
}
