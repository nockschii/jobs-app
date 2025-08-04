<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:60',
            'country' => 'nullable|string|max:60',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:50',
            'logo' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name Ihres Unternehmens ist erforderlich.',
            'email.required' => 'Die Unternehmens E-Mail ist erforderlich.',
            'email.email' => 'Die Unternehmens E-Mail muss eine gültige E-Mail-Adresse sein.',
            'website.url' => 'Die Website muss eine gültige URL sein.',
            'linkedin_url.url' => 'Die LinkedIn-URL muss eine gültige URL sein.',
        ];
    }
}
