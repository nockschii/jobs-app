<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_list(): void
    {
        Company::factory()->count(0)->create();

        $response = $this->get('/api/companies');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertEmpty(json_decode($content, true));
    }

    public function test_index_returns_company_list(): void
    {
        Company::factory()->count(1)->create();

        $response = $this->get('/api/companies');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertNotEmpty($decoded);
        $this->assertCount(1, $decoded);
    }

    public function test_show_company_with_id_returns_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->get('/api/companies/' . $company->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertNotEmpty($decoded);
        $this->assertEquals($company->id, $decoded['id']);
    }

    public function test_store_company_succeeds(): void
    {
        $companyData = Company::factory()->make();

        $response = $this->post('/api/companies', $companyData->toArray());

        $response->assertStatus(201);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($companyData->name, $decoded['name']);
        $this->assertDatabaseHas('companies', [
            'name' => $companyData->name,
            'email' => $companyData->email,
        ]);
    }

    public function test_update_company_succeeds(): void
    {
        $company = Company::factory()->create();
        $updatedData = Company::factory()->make()->toArray();

        $response = $this->put('/api/companies/' . $company->id, $updatedData);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $content = $response->getContent();
        $this->assertJson($content);
        $decoded = json_decode($content, true);
        $this->assertEquals($updatedData['name'], $decoded['name']);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $updatedData['name'],
        ]);
    }

    public function test_destroy_company_succeeds(): void
    {
        $company = Company::factory()->create();

        $response = $this->delete('/api/companies/' . $company->id);

        $response->assertStatus(204);
        $response->assertNoContent();

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }
}
