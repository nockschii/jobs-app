<?php

namespace App\Services\Search;

use App\Services\Search\SearchAlgorithmInterface;
use App\Models\Job;
use Illuminate\Support\Collection;

class ExactMatchAlgorithm implements SearchAlgorithmInterface
{
    public function search(string $searchterm, int $limit = 10): Collection
    {
        if (empty(trim($searchterm))) {
            return collect();
        }

        return Job::where('is_active', true)
            ->where(function ($query) use ($searchterm) {
                $query->where('title', '=', $searchterm)
                    ->orWhere('city', '=', $searchterm)
                    ->orWhere('country', '=', $searchterm);
            })
            ->limit($limit)
            ->get();
    }

    public function getName(): string
    {
        return 'exact';
    }
}
