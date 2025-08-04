<?php

namespace App\Http\Controllers;

use App\Services\Search\SearchService;
use Illuminate\Http\Request;
use App\Http\Requests\SearchTermRequest;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $searchterm = (string) $request->get('searchterm', '');
        $searchService = new SearchService();
        $results = $searchService->searchFlat($searchterm);

        return response()->json($results->toArray());
    }

    public function store(SearchTermRequest $request): JsonResponse
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
