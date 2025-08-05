<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchTermSearchRequest;
use App\Services\Search\SearchService;
use App\Http\Requests\SearchTermStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class SearchController extends Controller
{
    public function __construct(private SearchService $searchService) {}

    public function search(SearchTermSearchRequest $request): JsonResponse
    {
        $searchterm = (string) $request->get('searchterm', '');
        $results = $this->searchService->searchFlat($searchterm);

        return response()->json($results->toArray());
    }

    public function store(SearchTermStoreRequest $request): JsonResponse
    {
        $searchterm = $request->get('searchterm');
        $user = $request->user();
        $userInfo = $request->get('userInfo', []);

        $storedSearchTerm = $this->searchService->storeSearchTerm(
            $searchterm,
            $request->user() ? $request->user() : null,
            $userInfo
        );

        return response()->json([
            'message' => 'Search term stored successfully',
            'id' => $storedSearchTerm->id
        ], 201);
    }
}
