<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::all();

        return response()->json($companies);
    }

    public function show(int $id): JsonResponse
    {
        $company = Company::findOrFail($id);

        return response()->json($company);
    }

    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $company = Company::create($request->validated());

        return response()->json($company, 201);
    }

    public function update(CompanyUpdateRequest $request, int $id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->update($request->validated());

        return response()->json($company);
    }

    public function destroy(int $id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(null, 204);
    }
}
