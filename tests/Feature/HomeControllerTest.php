<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_route_returns_200()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_jobs_create_route_returns_200_for_authenticated_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/jobs/create');
        $response->assertStatus(200);
    }
}
