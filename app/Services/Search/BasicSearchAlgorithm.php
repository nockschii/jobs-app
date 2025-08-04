<?php

namespace App\Services\Search;

use App\Services\Search\SearchAlgorithmInterface;
use Illuminate\Support\Collection;
use App\Models\Job;

class BasicSearchAlgorithm implements SearchAlgorithmInterface
{
    public function search(string $searchterm, int $limit = 10): Collection
    {
        $query = Job::where('is_active', true);

        if (!empty(trim($searchterm))) {
            $query->where(function ($subQuery) use ($searchterm) {
                $subQuery->where('title', 'LIKE', '%' . $searchterm . '%')
                    ->orWhere('city', 'LIKE', '%' . $searchterm . '%')
                    ->orWhere('country', 'LIKE', '%' . $searchterm . '%');
            });
        }

        return $query->limit($limit)->get();
    }

    public function getName(): string
    {
        return 'basic';
    }
}
