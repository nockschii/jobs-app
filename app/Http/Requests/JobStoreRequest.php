<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Company;

class JobStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $company = Company::find($this->input('company_id'));

        if ($this->user() === null || $company === null) {
            return false;
        }

        return $this->user()->isOwnerOf($company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employmentTypes = implode(',', array_map(fn($type) => $type->value, \App\Enums\EmploymentType::cases()));
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'title' => 'required|string|max:120',
            'description' => 'nullable|string',
            'department' => 'required|string|max:50',
            'city' => 'required|string|max:60',
            'country' => 'required|string|max:60',
            'application_email' => 'required|email|max:255',
            'application_url' => 'required|url|max:200',
            'employment_type' => 'nullable|string|max:20|in:' . $employmentTypes,
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Company ID is required.',
            'title.required' => 'Job title is required.',
            'description.string' => 'Job description must be a string.',
            'department.required' => 'Department is required.',
            'city.required' => 'City is required.',
            'country.required' => 'Country is required.',
            'application_email.required' => 'Application email is required.',
            'application_url.required' => 'Application URL is required.',
            'employment_type.in' => 'Employment type must be one of the following: full_time, part_time, contract, internship, freelance.',
        ];
    }
}
