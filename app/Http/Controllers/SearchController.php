<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function search(Request $request): Response
    {
        $searchterm = $request->get('searchterm');

        if (empty($searchterm)) {
            return new Response([], 200, [
                'Content-Type' => 'application/json',
            ]);
        }

        $jobs = Job::where('is_active', true)
            ->where('title', 'LIKE', '%' . $searchterm . '%')
            ->orWhere('city', 'LIKE', '%' . $searchterm . '%')
            ->orWhere('country', 'LIKE', '%' . $searchterm . '%')
            ->get();

        return new Response($jobs, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
