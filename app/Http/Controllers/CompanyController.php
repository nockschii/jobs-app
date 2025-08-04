<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index(): Response
    {
        $companies = Company::all();
        return new Response($companies, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function show(int $id): Response
    {
        $company = Company::findOrFail($id);
        return new Response($company, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function store(Request $request): Response
    {
        $company = Company::create($request->all());
        return new Response($company, 201, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function update(Request $request, int $id): Response
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return new Response($company, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function destroy(int $id): Response
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return new Response(null, 204);
    }
}
