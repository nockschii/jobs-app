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
    public function index(): Response
    {
        $jobs = Job::all()->where('is_active', true);

        return new Response($jobs, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to get a job by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): Response
    {
        $job = Job::findOrFail($id);

        return new Response($job, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to store a new job.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $job = Job::create($request->all());

        return new Response($job, 201, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to update a job.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): Response
    {
        $job = Job::findOrFail($id);
        $job->update($request->all());

        return new Response($job, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to delete a job.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return new Response(null, 204);
    }
}
