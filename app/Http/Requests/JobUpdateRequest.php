<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Job;

class JobUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->user()) {
            return false;
        }

        $jobId = $this->route('job') ?? $this->route('id');
        $job = Job::with('company')->find($jobId);

        if (!$job || !$job->company) {
            return false;
        }

        return $this->user()->isOwnerOf($job->company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:120',
            'description' => 'nullable|string',
            'department' => 'sometimes|string|max:50',
            'city' => 'sometimes|string|max:60',
            'country' => 'sometimes|string|max:60',
            'application_email' => 'sometimes|email|max:255',
            'application_url' => 'sometimes|url|max:200',
            'employment_type' => 'nullable|string|max:15|in:full_time,part_time,contract,internship,freelance',
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
            'title.max' => 'The title may not be greater than 120 characters.',
            'department.max' => 'The department may not be greater than 50 characters.',
            'city.max' => 'The city may not be greater than 60 characters.',
            'country.max' => 'The country may not be greater than 60 characters.',
            'application_email.max' => 'The application email may not be greater than 255 characters.',
            'application_url.max' => 'The application URL may not be greater than 200 characters.',
            'employment_type.in' => 'The employment type must be one of the following: full_time, part_time, contract, internship, freelance.',
        ];
    }
}
