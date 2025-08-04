<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;

class JobController extends Controller
{
    public function index(): Response
    {
        $jobs = Job::with('company')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return new Response($jobs, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function show(int $id): Response
    {
        $job = Job::with('company')->findOrFail($id);

        return new Response($job, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function store(JobStoreRequest $request): Response
    {
        $job = Job::create($request->validated());

        return new Response($job, 201, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function update(JobUpdateRequest $request, int $id): Response
    {
        $job = Job::findOrFail($id);
        $job->update($request->validated());

        return new Response($job, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function destroy(int $id): Response
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return new Response(null, 204);
    }
}
