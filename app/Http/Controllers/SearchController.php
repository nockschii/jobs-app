<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchTermSearchRequest;
use App\Services\Search\SearchService;
use App\Http\Requests\SearchTermStoreRequest;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function search(SearchTermSearchRequest $request): JsonResponse
    {
        $searchterm = (string) $request->get('searchterm', '');
        $searchService = new SearchService();
        $results = $searchService->searchFlat($searchterm);

        return response()->json($results->toArray());
    }

    public function store(SearchTermStoreRequest $request): JsonResponse
    {
        $searchterm = $request->get('searchterm');
        $user = $request->user();
        $userInfo = $request->get('userInfo', []);

        $searchService = new SearchService();
        $storedSearchTerm = $searchService->storeSearchTerm(
            $searchterm,
            $user ? $user : null,
            $userInfo
        );

        return response()->json([
            'message' => 'Search term stored successfully',
            'id' => $storedSearchTerm->id
        ], 201);
    }
}
