<?php

namespace Tests\Feature\Services\Search;

use Tests\TestCase;
use App\Services\Search\BasicSearchAlgorithm;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;

class BasicSearchAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    private BasicSearchAlgorithm $algorithm;

    protected function setUp(): void
    {
        parent::setUp();
        $this->algorithm = new BasicSearchAlgorithm();
    }

    public function test_get_name_returns_basic(): void
    {
        $this->assertEquals('basic', $this->algorithm->getName());
    }

    public function test_search_by_title_returns_matching_records(): void
    {
        Job::factory()->create(['title' => 'enthält_suchbegriff_hier']);
        Job::factory()->create(['title' => 'auch_suchbegriff_enthalten']);
        Job::factory()->create(['title' => 'kein_treffer']);

        $results = $this->algorithm->search('suchbegriff');

        $this->assertCount(2, $results);
        $this->assertStringContainsString('suchbegriff', $results[0]['title']);
        $this->assertStringContainsString('suchbegriff', $results[1]['title']);
    }

    public static function searchFieldProvider(): array
    {
        return [
            'city' => ['city', 'wert_mit_begriff', 'begriff'],
            'country' => ['country', 'enthält_term', 'term'],
            'title' => ['title', 'hat_wort_drin', 'wort'],
        ];
    }

    #[DataProvider('searchFieldProvider')]
    public function test_search_by_field_with_matching_term_returns_record_successfully(string $field, string $fieldValue, string $searchTerm): void
    {
        Job::factory()->create([$field => $fieldValue]);
        Job::factory()->create([$field => 'anderer_wert']);

        $results = $this->algorithm->search($searchTerm);

        $this->assertCount(1, $results);
        $this->assertEquals($fieldValue, $results[0][$field]);
    }

    #[DataProvider('searchFieldProvider')]
    public function test_search_by_field_with_non_matching_term_returns_empty_list(string $field, string $fieldValue, string $ignoredTerm): void
    {
        Job::factory()->create([$field => $fieldValue]);

        $results = $this->algorithm->search('nicht_vorhanden');

        $this->assertCount(0, $results);
    }

    public function test_search_with_limit_returns_limited_results(): void
    {
        Job::factory()->count(10)->create(['title' => 'wiederholter_wert']);

        $results = $this->algorithm->search('wiederholter_wert', 3);

        $this->assertCount(3, $results);
    }

    public function test_search_excludes_inactive_records_successfully(): void
    {
        Job::factory()->create(['title' => 'aktiver_eintrag']);
        Job::factory()->create(['title' => 'aktiver_eintrag', 'is_active' => false]);

        $results = $this->algorithm->search('aktiver_eintrag');

        $this->assertCount(1, $results);
        $this->assertTrue((bool) $results[0]['is_active']);
    }

    #[DataProvider('emptySearchProvider')]
    public function test_search_with_empty_or_nonexistent_term_returns_empty_list(string $searchTerm): void
    {
        Job::factory()->create(['title' => 'vorhandener_eintrag']);

        $results = $this->algorithm->search($searchTerm);

        $this->assertCount(0, $results);
    }

    public static function emptySearchProvider(): array
    {
        return [
            'nicht existierender begriff' => ['begriff_nicht_in_datenbank'],
        ];
    }

    public function test_search_with_empty_string_returns_all_active_records(): void
    {
        Job::factory()->create(['title' => 'erster_eintrag']);
        Job::factory()->create(['title' => 'zweiter_eintrag']);
        Job::factory()->create(['title' => 'dritter_eintrag', 'is_active' => false]);

        $results = $this->algorithm->search('');

        $this->assertCount(2, $results);
        $this->assertTrue((bool) $results[0]['is_active']);
        $this->assertTrue((bool) $results[1]['is_active']);
    }

    public function test_search_across_multiple_fields_returns_combined_results(): void
    {
        Job::factory()->create(['title' => 'titel_mit_abfrage']);
        Job::factory()->create(['city' => 'stadt_mit_abfrage']);
        Job::factory()->create(['country' => 'land_mit_abfrage']);

        $results = $this->algorithm->search('abfrage');

        $this->assertCount(3, $results);
    }
}
