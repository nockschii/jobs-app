<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;

class JobApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
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
