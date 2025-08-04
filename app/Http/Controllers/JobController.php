<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Job;

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

    public function store(Request $request): Response
    {
        $job = Job::create($request->all());

        return new Response($job, 201, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function update(Request $request, int $id): Response
    {
        $job = Job::findOrFail($id);
        $job->update($request->all());

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
