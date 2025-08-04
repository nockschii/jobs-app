<?php

namespace Tests\Feature\Services\Search;

use Tests\TestCase;
use App\Services\Search\ExactMatchAlgorithm;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

class ExactMatchAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    private ExactMatchAlgorithm $algorithm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->algorithm = new ExactMatchAlgorithm();
    }

    public function test_get_name_returns_exact(): void
    {
        $this->assertEquals('exact', $this->algorithm->getName());
    }

    public function test_search_with_exact_title_match_returns_matching_record(): void
    {
        Job::factory()->create(['title' => 'suchbegriff']);
        Job::factory()->create(['title' => 'enthält_suchbegriff_hier']);
        Job::factory()->create(['title' => 'kein_treffer']);

        $results = $this->algorithm->search('suchbegriff');

        $this->assertCount(1, $results);
        $this->assertEquals('suchbegriff', $results->first()['title']);
    }

    public function test_search_with_partial_match_returns_empty_collection(): void
    {
        Job::factory()->create(['title' => 'enthält_suchbegriff_hier']);
        Job::factory()->create(['title' => 'auch_suchbegriff_enthalten']);

        $results = $this->algorithm->search('suchbegriff');

        $this->assertCount(0, $results);
    }

    public static function exactMatchFieldProvider(): array
    {
        return [
            'city field' => ['city', 'wien', 'wien'],
            'country field' => ['country', 'österreich', 'österreich'],
            'title field' => ['title', 'entwickler', 'entwickler'],
        ];
    }

    #[DataProvider('exactMatchFieldProvider')]
    public function test_search_by_field_with_exact_match_returns_record_successfully(string $field, string $fieldValue, string $searchTerm): void
    {
        Job::factory()->create([$field => $fieldValue]);
        Job::factory()->create([$field => 'anderer_wert']);
        Job::factory()->create([$field => 'noch_ein_' . $fieldValue]); // Partial match

        $results = $this->algorithm->search($searchTerm);

        $this->assertCount(1, $results);
        $this->assertEquals($fieldValue, $results->first()[$field]);
    }

    #[DataProvider('exactMatchFieldProvider')]
    public function test_search_by_field_with_partial_match_returns_empty_collection(string $field, string $fieldValue, string $searchTerm): void
    {
        Job::factory()->create([$field => 'enthält_' . $fieldValue . '_hier']);
        Job::factory()->create([$field => $fieldValue . '_erweitert']);

        $results = $this->algorithm->search($searchTerm);

        $this->assertCount(0, $results);
    }

    public function test_search_with_limit_returns_limited_results(): void
    {
        Job::factory()->count(10)->create(['title' => 'gleicher_titel']);

        $results = $this->algorithm->search('gleicher_titel', 3);

        $this->assertCount(3, $results);
    }

    public function test_search_excludes_inactive_records_successfully(): void
    {
        Job::factory()->create(['title' => 'exakter_begriff']);
        Job::factory()->create(['title' => 'exakter_begriff', 'is_active' => false]);

        $results = $this->algorithm->search('exakter_begriff');

        $this->assertCount(1, $results);
        $this->assertTrue((bool) $results->first()['is_active']);
    }

    public function test_search_with_empty_string_returns_empty_collection(): void
    {
        Job::factory()->create(['title' => 'vorhandener_eintrag']);

        $results = $this->algorithm->search('');

        $this->assertCount(0, $results);
    }

    public function test_search_with_whitespace_only_returns_empty_collection(): void
    {
        Job::factory()->create(['title' => 'vorhandener_eintrag']);

        $results = $this->algorithm->search('   ');

        $this->assertCount(0, $results);
    }

    public function test_search_with_nonexistent_term_returns_empty_collection(): void
    {
        Job::factory()->create(['title' => 'vorhandener_eintrag']);

        $results = $this->algorithm->search('nicht_vorhanden');

        $this->assertCount(0, $results);
    }

    public function test_search_is_case_insensitive_in_mysql(): void
    {
        // MySQL ist standardmäßig case-insensitive
        Job::factory()->create(['title' => 'Begriff']);
        Job::factory()->create(['title' => 'anderer_titel']);

        $resultsUpper = $this->algorithm->search('Begriff');
        $resultsLower = $this->algorithm->search('begriff');

        // Beide sollten den gleichen Job finden (MySQL case-insensitive)
        $this->assertCount(1, $resultsUpper);
        $this->assertCount(1, $resultsLower);
        $this->assertEquals('Begriff', $resultsUpper->first()['title']);
        $this->assertEquals('Begriff', $resultsLower->first()['title']);
    }

    public function test_search_across_multiple_fields_returns_exact_matches_only(): void
    {
        Job::factory()->create(['title' => 'abfrage']);
        Job::factory()->create(['city' => 'abfrage']);
        Job::factory()->create(['country' => 'abfrage']);
        // Diese sollten NICHT gefunden werden (partial matches):
        Job::factory()->create(['title' => 'abfrage_erweitert']);
        Job::factory()->create(['city' => 'stadt_mit_abfrage']);

        $results = $this->algorithm->search('abfrage');

        $this->assertCount(3, $results);
        // Alle sollten exakt 'abfrage' enthalten
        $this->assertTrue($results->every(function ($job) {
            return $job['title'] === 'abfrage' ||
                $job['city'] === 'abfrage' ||
                $job['country'] === 'abfrage';
        }));
    }

    public function test_search_returns_collection_instance(): void
    {
        Job::factory()->create(['title' => 'test_eintrag']);

        $results = $this->algorithm->search('test_eintrag');

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $results);
    }
}
