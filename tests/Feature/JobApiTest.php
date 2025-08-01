<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;

class JobApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_jobs_returns_empty_list(): void
    {
        Job::factory()->count(0)->create();

        $response = $this->get('/api/jobs');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertEmpty(json_decode($content, true));
    }

    public function test_get_jobs_returns_job_list(): void
    {
        Job::factory()->count(1)->create();

        $response = $this->get('/api/jobs');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertNotEmpty(json_decode($content, true));
    }
}
