<?php

namespace Tests\Feature;

use App\Enums\EmploymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;
use App\Models\Company;
use App\Models\User;

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
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id, ['role' => 'owner']);
        $jobData = Job::factory()->make(['company_id' => $company->id]);

        $response = $this->actingAs($user)->postJson('/api/jobs', $jobData->toArray());

        $response->assertStatus(201);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($jobData->title, $decoded['title']);
        $this->assertEquals($company->id, $decoded['company_id']);
        $this->assertDatabaseHas('jobs', [
            'title' => $jobData->title,
            'description' => $jobData->description,
            'company_id' => $company->id,
        ]);
    }

    public function test_update_job_succeeds(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id, ['role' => 'owner']);

        $job = Job::factory()->create(['company_id' => $company->id]);

        $updatedData = [
            'title' => 'Updated Job Title',
            'department' => 'Updated Department',
        ];

        $response = $this->actingAs($user)->putJson('/api/jobs/' . $job->id, $updatedData);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($updatedData['title'], $decoded['title']);
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => $updatedData['title'],
            'department' => $updatedData['department'],
        ]);
    }

    public function test_destroy_job_succeeds(): void
    {
        $this->withoutMiddleware();

        $job = Job::factory()->create();

        $response = $this->delete('/api/jobs/' . $job->id);

        $response->assertStatus(204);
        $response->assertNoContent();

        $this->assertDatabaseMissing('jobs', [
            'id' => $job->id,
        ]);
    }

    public function test_index_returns_jobs_in_correct_order(): void
    {
        // oldest job
        Job::factory()->create([
            'title' => 'oldest job',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // inbetween job
        Job::factory()->create([
            'title' => 'inbetween job',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // latest job
        Job::factory()->create([
            'title' => 'latest job',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/api/jobs');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);

        $this->assertCount(3, $decoded);

        $this->assertEquals('latest job', $decoded[0]['title']);
        $this->assertEquals('inbetween job', $decoded[1]['title']);
        $this->assertEquals('oldest job', $decoded[2]['title']);

        $timestamp0 = strtotime($decoded[0]['created_at']);
        $timestamp1 = strtotime($decoded[1]['created_at']);
        $timestamp2 = strtotime($decoded[2]['created_at']);
        $this->assertTrue($timestamp0 >= $timestamp1);
        $this->assertTrue($timestamp1 >= $timestamp2);
    }

    public function test_store_job_authorizes_company_owner(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $user->companies()->attach($company->id, ['role' => 'owner']);

        $jobData = [
            'company_id' => $company->id,
            'title' => 'Test Job',
            'description' => 'Test Description',
            'department' => 'IT',
            'city' => 'Vienna',
            'country' => 'Austria',
            'application_email' => 'test@company.com',
            'application_url' => 'https://company.com/apply',
            'employment_type' => EmploymentType::FullTime->value,
        ];

        $response = $this->actingAs($user)->postJson('/api/jobs', $jobData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('jobs', [
            'title' => 'Test Job',
            'company_id' => $company->id,
        ]);
    }

    public function test_store_job_denies_non_company_owner(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $user->companies()->attach($company->id, ['role' => 'employee']);

        $jobData = [
            'company_id' => $company->id,
            'title' => 'Test Job',
            'description' => 'Test Description',
            'department' => 'IT',
            'city' => 'Vienna',
            'country' => 'Austria',
            'application_email' => 'test@company.com',
            'application_url' => 'https://company.com/apply',
            'employment_type' => EmploymentType::FullTime->value,
        ];

        $response = $this->actingAs($user)->postJson('/api/jobs', $jobData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('jobs', [
            'title' => 'Test Job',
        ]);
    }
}
