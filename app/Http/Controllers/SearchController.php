<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function search(Request $request): Response
    {
        $title = $request->get('searchterm');

        if (empty($title)) {
            return new Response([], 200, [
                'Content-Type' => 'application/json',
            ]);
        }

        $jobs = Job::where('is_active', true)
            ->where('title', 'LIKE', '%' . $title . '%')
            ->get();

        return new Response($jobs, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
