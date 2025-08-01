<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Handle the incoming request to get jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJobs(): Response
    {

        $jobs = Job::all()->where('is_active', true);

        return new Response($jobs, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
