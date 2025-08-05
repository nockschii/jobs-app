<?php

namespace App\Services\Search;

use App\Models\SearchTerm;
use App\Services\Search\BasicSearchAlgorithm;
use App\Services\Search\ExactMatchAlgorithm;
use Illuminate\Support\Collection;
use App\Models\User;

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

    public function storeSearchTerm(string $searchterm, ?User $user, array $userInfo): SearchTerm
    {
        $resultsCount = $this->searchFlat($searchterm)->count();

        return SearchTerm::create([
            'term' => $searchterm,
            'results_count' => $resultsCount,
            'user_id' => $user?->id,
            'ip_address' => $userInfo['ip_address'] ?? request()->ip(),
            'user_agent' => $userInfo['user_agent'] ?? request()->header('User-Agent'),
            'session_id' => $userInfo['session_id'] ?? session()->getId(),
            'referer' => $userInfo['referer'] ?? request()->header('Referer'),
            'platform' => $userInfo['platform'] ?? null,
            'screen' => $userInfo['screen'] ?? null,
        ]);
    }
}
