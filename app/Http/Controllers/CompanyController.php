<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Handle the incoming request to get companies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $companies = Company::all();

        return new Response($companies, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to get a company by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): Response
    {
        $company = Company::findOrFail($id);

        return new Response($company, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to store a new company.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $company = Company::create($request->all());

        return new Response($company, 201, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to update a company.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): Response
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return new Response($company, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Handle the incoming request to delete a company.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return new Response(null, 204);
    }
}
