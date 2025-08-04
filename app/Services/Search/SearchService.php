<?php

namespace App\Services\Search;

use App\Services\Search\BasicSearchAlgorithm;
use App\Services\Search\ExactMatchAlgorithm;
use Illuminate\Support\Collection;

class SearchService
{
    private int $resultsPerAlgorithm = 4;

    private function getActiveAlgorithms(): array
    {
        return [
            new BasicSearchAlgorithm(),
            new ExactMatchAlgorithm(),
        ];
    }

    public function search(string $searchterm): Collection
    {
        $algorithms = $this->getActiveAlgorithms();
        $results = collect();
        $usedJobIds = collect();

        foreach ($algorithms as $algorithm) {
            $algorithmResults = $algorithm->search($searchterm, $this->resultsPerAlgorithm * 2);

            $filteredResults = $algorithmResults->filter(function ($job) use ($usedJobIds) {
                if ($usedJobIds->contains($job['id'])) {
                    return false;
                }
                $usedJobIds->push($job['id']);
                return true;
            });

            $filteredResults = $filteredResults->take($this->resultsPerAlgorithm);

            if ($filteredResults->isNotEmpty()) {
                $results->push([
                    'algorithm' => $algorithm->getName(),
                    'jobs' => $filteredResults->values()
                ]);
            }
        }

        return $results;
    }

    public function searchFlat(string $searchterm): Collection
    {
        $results = $this->search($searchterm);
        $flatResults = collect();

        $results->each(function ($algorithmResult) use ($flatResults) {
            $algorithmResult['jobs']->each(function ($job) use ($flatResults, $algorithmResult) {
                $job['found_by_algorithm'] = $algorithmResult['algorithm'];
                $flatResults->push($job);
            });
        });

        return $flatResults;
    }
}
