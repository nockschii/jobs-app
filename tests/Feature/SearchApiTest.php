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

    public function test_search_returns_empty_array_when_no_criteria(): void
    {
        $response = $this->get('/api/search');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    #[DataProvider('searchDataProvider')]
    public function test_singular_word_search_exact_match_returns_matching_job(string $field, string $value, string $searchTerm): void
    {
        $company = Company::factory()->create();
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'is_active' => true,
            $field => $value
        ]);

        $response = $this->get('/api/search?searchterm=' . $searchTerm);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $content = json_decode($response->getContent(), true);
        $this->assertCount(1, $content);
        $this->assertEquals($job->id, $content[0]['id']);
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
    public function test_search_returns_multiple_matching_jobs(string $searchTerm, array $expectedResults): void
    {
        $company = Company::factory()->create();

        $jobs = [];
        foreach ($expectedResults as $result) {
            $jobs[] = Job::factory()->create([
                'company_id' => $company->id,
                'is_active' => true,
                'title' => $result
            ]);
        }

        $response = $this->get('/api/search?searchterm=' . $searchTerm);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $content = json_decode($response->getContent(), true);
        $this->assertCount(count($expectedResults), $content);

        $returnedIds = array_column($content, 'id');
        foreach ($jobs as $job) {
            $this->assertContains($job->id, $returnedIds);
        }
    }

    public static function similarMatchDataProvider(): array
    {
        return [
            ['Developer', ['PHP Developer', 'Java Developer']],
            ['Script', ['JavaScript Developer', 'TypeScript Engineer']],
            ['Engineer', ['Software Engineer', 'DevOps Engineer']],
        ];
    }

    public function test_search_returns_empty_array_when_no_matching_jobs(): void
    {
        $response = $this->get('/api/search?searchterm=NonExistentJob');
        $response->assertStatus(200);
        $response->assertJson([]);
    }
}
