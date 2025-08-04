<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\SearchTermRequest;
use Illuminate\Support\Facades\Validator;

class SearchTermRequestTest extends TestCase
{
    protected SearchTermRequest $request;
    protected array $rules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new SearchTermRequest();
        $this->rules = $this->request->rules();
    }

    public function test_validates_required_fields(): void
    {
        $validator = Validator::make([], $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('searchterm', $validator->errors()->toArray());
    }

    public function test_validates_field_max_length(): void
    {
        $data = [
            'searchterm' => str_repeat('x', 256),
        ];

        $validator = Validator::make($data, $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('searchterm', $validator->errors()->toArray());
    }

    public function test_validates_string_type(): void
    {
        $data = [
            'searchterm' => 123, // Nicht String
        ];

        $validator = Validator::make($data, $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('searchterm', $validator->errors()->toArray());
    }

    public function test_passes_validation_with_valid_data(): void
    {
        $data = [
            'searchterm' => 'PHP Developer',
        ];

        $validator = Validator::make($data, $this->rules);

        $this->assertFalse($validator->fails());

        $validated = $validator->validated();
        $this->assertEquals('PHP Developer', $validated['searchterm']);
    }

    public function test_fails_with_empty_searchterm(): void
    {
        $data = [
            'searchterm' => '',
        ];

        $validator = Validator::make($data, $this->rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('searchterm', $validator->errors()->toArray());
    }
}
