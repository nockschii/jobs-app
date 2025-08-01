<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;

class JobApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_list(): void
    {
        Job::factory()->count(0)->create();

        $response = $this->get('/api/jobs');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertEmpty(json_decode($content, true));
    }

    public function test_index_returns_job_list(): void
    {
        Job::factory()->count(1)->create();

        $response = $this->get('/api/jobs');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertNotEmpty($decoded);
        $this->assertCount(1, $decoded);
    }

    public function test_show_job_with_id_returns_job(): void
    {
        $job = Job::factory()->create();

        $response = $this->get('/api/jobs/' . $job->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertNotEmpty($decoded);
        $this->assertEquals($job->id, $decoded['id']);
    }

    public function test_store_job_succeeds(): void
    {
        $jobData = Job::factory()->make();

        $response = $this->post('/api/jobs', $jobData->toArray());

        $response->assertStatus(201);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($jobData->title, $decoded['title']);
        $this->assertDatabaseHas('jobs', [
            'title' => $jobData->title,
            'description' => $jobData->description,
        ]);
    }

    public function test_update_job_succeeds(): void
    {
        $job = Job::factory()->create();
        $updatedData = Job::factory()->make()->toArray();

        $response = $this->put('/api/jobs/' . $job->id, $updatedData);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($updatedData['title'], $decoded['title']);
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => $updatedData['title'],
        ]);
    }

    public function test_destroy_job_succeeds(): void
    {
        $job = Job::factory()->create();

        $response = $this->delete('/api/jobs/' . $job->id);

        $response->assertStatus(204);
        $response->assertNoContent();

        $this->assertDatabaseMissing('jobs', [
            'id' => $job->id,
        ]);
    }
}
