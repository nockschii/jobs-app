<?php

namespace Tests\Unit\Requests;

use App\Enums\EmploymentType;
use Tests\TestCase;
use App\Http\Requests\JobStoreRequest;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class JobStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::factory()->create();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_validates_required_fields(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('company_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertArrayHasKey('department', $validator->errors()->toArray());
        $this->assertArrayHasKey('city', $validator->errors()->toArray());
        $this->assertArrayHasKey('country', $validator->errors()->toArray());
        $this->assertArrayHasKey('application_email', $validator->errors()->toArray());
        $this->assertArrayHasKey('application_url', $validator->errors()->toArray());
    }

    public function test_validates_field_max_lengths(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => $this->company->id,
            'title' => str_repeat('x', 121),
            'department' => str_repeat('x', 51),
            'city' => str_repeat('x', 61),
            'country' => str_repeat('x', 61),
            'application_email' => str_repeat('x', 250) . '@test.com',
            'application_url' => 'https://' . str_repeat('x', 190) . '.com',
            'employment_type' => str_repeat('x', 21),
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertArrayHasKey('department', $validator->errors()->toArray());
        $this->assertArrayHasKey('city', $validator->errors()->toArray());
        $this->assertArrayHasKey('country', $validator->errors()->toArray());
        $this->assertArrayHasKey('application_email', $validator->errors()->toArray());
        $this->assertArrayHasKey('application_url', $validator->errors()->toArray());
        $this->assertArrayHasKey('employment_type', $validator->errors()->toArray());
    }

    public function test_validates_boundary_values_successfully(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => $this->company->id,
            'title' => 'Valid Title',
            'department' => 'IT',
            'city' => 'Vienna',
            'country' => 'Austria',
            'application_email' => 'test@example.com',
            'application_url' => 'https://example.com',
            'employment_type' => EmploymentType::FullTime->value,
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }

    public function test_validates_email_format(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $invalidEmails = [
            'invalid-email',
            'test@',
            '@domain.com',
            'test.domain.com',
            'test..test@domain.com'
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $data = $this->makeValidJobData();
            $data['application_email'] = $invalidEmail;

            $validator = Validator::make($data, $rules);

            $this->assertTrue($validator->fails(), "Email '{$invalidEmail}' should be invalid");
            $this->assertArrayHasKey('application_email', $validator->errors()->toArray());
        }
    }

    public function test_validates_url_format(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $invalidUrls = [
            'not-a-url',
            'just-text',
            'www.example.com',
            'example',
        ];

        foreach ($invalidUrls as $invalidUrl) {
            $data = $this->makeValidJobData();
            $data['application_url'] = $invalidUrl;

            $validator = Validator::make($data, $rules);

            $this->assertTrue($validator->fails());
            $this->assertArrayHasKey('application_url', $validator->errors()->toArray());
        }
    }

    public function test_validates_employment_type_enum(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $invalidTypes = [
            'invalid_type',
            'permanent',
            'temporary',
            'consultant',
            'full-time',
            'full_time',
            'part_time',
        ];

        foreach ($invalidTypes as $invalidType) {
            $data = $this->makeValidJobData();
            $data['employment_type'] = $invalidType;

            $validator = Validator::make($data, $rules);

            $this->assertTrue($validator->fails(), "Employment type '{$invalidType}' should be invalid");
            $this->assertArrayHasKey('employment_type', $validator->errors()->toArray());
        }
    }

    public function test_accepts_valid_employment_types(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $validTypes = array_map(fn($type) => $type->value, EmploymentType::cases());

        foreach ($validTypes as $type) {
            $data = $this->makeValidJobData();
            $data['employment_type'] = $type;

            $validator = Validator::make($data, $rules);

            $this->assertFalse($validator->fails(), "Employment type '{$type}' should be valid");
        }
    }

    public function test_allows_nullable_fields(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => $this->company->id,
            'title' => 'Valid Title',
            'department' => 'IT',
            'city' => 'Wien',
            'country' => 'Österreich',
            'application_email' => 'valid@email.com',
            'application_url' => 'https://valid-url.com',
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }

    public function test_validates_string_types(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => $this->company->id,
            'title' => 123,
            'department' => ['array'],
            'city' => true,
            'country' => null,
            'application_email' => 'valid@email.com',
            'application_url' => 'https://valid-url.com',
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
        $this->assertArrayHasKey('department', $validator->errors()->toArray());
        $this->assertArrayHasKey('city', $validator->errors()->toArray());
        $this->assertArrayHasKey('country', $validator->errors()->toArray());
    }

    public function test_passes_validation_with_valid_data(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = $this->makeValidJobData();

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());

        $validated = $validator->validated();
        $this->assertEquals($data['title'], $validated['title']);
        $this->assertEquals($data['department'], $validated['department']);
        $this->assertEquals($data['application_email'], $validated['application_email']);
    }

    public function test_handles_empty_strings_vs_null(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => $this->company->id,
            'title' => '',
            'description' => '',
            'department' => 'IT',
            'city' => 'Wien',
            'country' => 'Österreich',
            'application_email' => 'valid@email.com',
            'application_url' => 'https://valid-url.com',
            'employment_type' => '',
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    public function test_validates_company_id_exists(): void
    {
        $request = new JobStoreRequest();
        $rules = $request->rules();

        $data = [
            'company_id' => 99999,
            'title' => 'Valid Title',
            'department' => 'IT',
            'city' => 'Wien',
            'country' => 'Österreich',
            'application_email' => 'test@example.com',
            'application_url' => 'https://example.com',
            'employment_type' => EmploymentType::FullTime->value,
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('company_id', $validator->errors()->toArray());
    }

    protected function makeValidJobData(): array
    {
        return [
            'company_id' => $this->company->id,
            'title' => 'Elektriker',
            'description' => 'TestJob Beschreibung',
            'department' => 'Technik',
            'city' => 'Wien',
            'country' => 'Österreich',
            'application_email' => 'jobs@company.com',
            'application_url' => 'https://company.com/apply',
            'employment_type' => EmploymentType::FullTime->value,
        ];
    }
}
