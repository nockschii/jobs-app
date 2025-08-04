<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_all_active_jobs_when_no_criteria(): void
    {
        $company = Company::factory()->create();
        Job::factory()->count(2)->create([
            'company_id' => $company->id,
            'is_active' => true
        ]);
        Job::factory()->create([
            'company_id' => $company->id,
            'is_active' => false
        ]);

        $response = $this->get('/api/search');
        $response->assertStatus(200);
        
        $content = json_decode($response->getContent(), true);
        $this->assertCount(2, $content);
    }

    public function test_search_returns_empty_array_when_no_matching_jobs(): void
    {
        $company = Company::factory()->create();
        Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Existing Job',
            'is_active' => true
        ]);

        $response = $this->get('/api/search?searchterm=NonExistentJob');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    #[DataProvider('searchDataProvider')]
    public function test_search_by_field_returns_matching_job(string $field, string $value, string $searchTerm): void
    {
        $company = Company::factory()->create();
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'is_active' => true,
            $field => $value
        ]);

        $response = $this->get('/api/search?searchterm=' . urlencode($searchTerm));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $content = json_decode($response->getContent(), true);

        $this->assertGreaterThan(0, count($content), "No results found for search term: {$searchTerm}");
        $this->assertEquals($job->id, $content[0]['id']);
        $this->assertArrayHasKey('found_by_algorithm', $content[0]);
    }

    public static function searchDataProvider(): array
    {
        return [
            'search by title' => ['title', 'PHP Developer', 'PHP'],
            'search by city' => ['city', 'Vienna', 'Vienna'],
            'search by country' => ['country', 'Austria', 'Austria'],
        ];
    }

    #[DataProvider('similarMatchDataProvider')]
    public function test_search_returns_multiple_matching_jobs(string $searchTerm, array $jobTitles): void
    {
        $company = Company::factory()->create();

        $jobs = [];
        foreach ($jobTitles as $title) {
            $jobs[] = Job::factory()->create([
                'company_id' => $company->id,
                'title' => $title,
                'is_active' => true
            ]);
        }

        $response = $this->get('/api/search?searchterm=' . urlencode($searchTerm));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $content = json_decode($response->getContent(), true);

        $this->assertCount(count($jobTitles), $content);

        $returnedIds = array_column($content, 'id');
        foreach ($jobs as $job) {
            $this->assertContains($job->id, $returnedIds);
        }
    }

    public static function similarMatchDataProvider(): array
    {
        return [
            'developer search' => ['Developer', ['PHP Developer', 'Java Developer']],
            'php search' => ['PHP', ['Senior PHP Developer', 'Junior PHP Developer']],
            'script search' => ['Script', ['JavaScript Developer', 'TypeScript Engineer']],
            'engineer search' => ['Engineer', ['Software Engineer', 'DevOps Engineer']],
        ];
    }

    public function test_search_excludes_inactive_jobs_returns_active_jobs(): void
    {
        $company = Company::factory()->create();

        $activeJob = Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'PHP Developer',
            'is_active' => true
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'PHP Developer',
            'is_active' => false
        ]);

        $response = $this->get('/api/search?searchterm=PHP');

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);

        $this->assertGreaterThan(0, count($content), "No results found for PHP search");
        $this->assertEquals($activeJob->id, $content[0]['id']);
        $this->assertTrue((bool) $content[0]['is_active']);
    }

    public function test_search_is_case_insensitive_returns_jobs(): void
    {
        $company = Company::factory()->create();

        $jobs = [
            Job::factory()->create([
                'company_id' => $company->id,
                'title' => 'PHP Developer',
                'is_active' => true
            ]),
            Job::factory()->create([
                'company_id' => $company->id,
                'title' => 'php developer',
                'is_active' => true
            ])
        ];

        $response = $this->get('/api/search?searchterm=PHP');

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);

        $this->assertCount(2, $content);

        $returnedIds = array_column($content, 'id');
        foreach ($jobs as $job) {
            $this->assertContains($job->id, $returnedIds);
        }
    }

    public function test_search_with_empty_searchterm_returns_all_active_jobs(): void
    {
        $company = Company::factory()->create();

        Job::factory()->count(3)->create([
            'company_id' => $company->id,
            'is_active' => true
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'is_active' => false
        ]);

        $response = $this->get('/api/search?searchterm=');

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertCount(3, $content);

        foreach ($content as $job) {
            $this->assertTrue((bool) $job['is_active']);
        }
    }

    public function test_search_response_contains_algorithm_info(): void
    {
        $company = Company::factory()->create();
        Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Test Job',
            'is_active' => true
        ]);

        $response = $this->get('/api/search?searchterm=Test');

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertGreaterThan(0, count($content));
        $this->assertArrayHasKey('found_by_algorithm', $content[0]);
        $this->assertNotEmpty($content[0]['found_by_algorithm']);
    }

    public function test_search_removes_duplicates_across_algorithms(): void
    {
        $company = Company::factory()->create();

        Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Developer'
        ]);
        Job::factory()->create([
            'company_id' => $company->id,
            'title' => 'Senior Developer'  
        ]);

        $response = $this->get('/api/search?searchterm=Developer');

        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);


        $this->assertCount(2, $content, 'Should return 2 unique jobs, no duplicates');

        $jobIds = array_column($content, 'id');
        $uniqueJobIds = array_unique($jobIds);
        $this->assertCount(2, $uniqueJobIds, 'All returned jobs should have unique IDs');

        foreach ($content as $job) {
            $this->assertArrayHasKey('found_by_algorithm', $job);
            $this->assertNotEmpty($job['found_by_algorithm']);
        }

        $jobTitles = array_column($content, 'title');
        $this->assertContains('Developer', $jobTitles);
        $this->assertContains('Senior Developer', $jobTitles);
    }
}
