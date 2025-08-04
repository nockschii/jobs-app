<?php

namespace App\Services\Search;

use Illuminate\Support\Collection;

interface SearchAlgorithmInterface
{
    public function search(string $searchterm, int $limit = 10): Collection;
    public function getName(): string;
}
