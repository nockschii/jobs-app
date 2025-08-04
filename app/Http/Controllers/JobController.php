<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    public function index(): JsonResponse
    {
        $jobs = Job::with('company')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($jobs);
    }

    public function show(int $id): JsonResponse
    {
        $job = Job::with('company')->findOrFail($id);

        return response()->json($job);
    }

    public function store(JobStoreRequest $request): JsonResponse
    {
        $job = Job::create($request->validated());

        return response()->json($job, 201);
    }

    public function update(JobUpdateRequest $request, int $id): JsonResponse
    {
        $job = Job::findOrFail($id);
        $job->update($request->validated());

        return response()->json($job);
    }

    public function destroy(int $id): JsonResponse
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(null, 204);
    }
}
