<?php

namespace App\Http\Controllers;

use App\Services\Search\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function search(Request $request): Response
    {
        $searchterm = (string) $request->get('searchterm', '');

        $searchService = new SearchService();
        $results = $searchService->searchFlat($searchterm);

        return new Response($results->toArray(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
