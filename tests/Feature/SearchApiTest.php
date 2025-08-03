<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_empty_array_when_no_criteria(): void
    {
        $response = $this->get('/api/search');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_search_by_title_returns_matching_job(): void
    {
        $company = Company::factory()->create();
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'PHP Developer',
            'is_active' => true
        ]);

        $response = $this->get('/api/search?searchterm=PHP');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $content = json_decode($response->getContent(), true);
        $this->assertCount(1, $content);
        $this->assertEquals($job->id, $content[0]['id']);
    }
}
