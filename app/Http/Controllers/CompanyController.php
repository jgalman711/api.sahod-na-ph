<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\BaseResource;
use App\Models\Company;
use App\Traits\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use Filter;

    public function index(Request $request): JsonResponse
    {
        $companies = $this->applyFilters($request, Company::query(), [
            'name'
        ]);
        return $this->sendResponse($companies, 'Companies retrieved successfully.');
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['slug'] = strtolower(str_replace(' ', '-', $input['name']));
        $input['status'] = Company::STATUS_ACTIVE;
        if (isset($input['logo']) && $input['logo']) {
            $input['logo'] = Company::STORAGE_PATH . time() . '.' . $request->logo->extension();
            $request->logo->storeAs(Company::STORAGE_PATH, $input['logo']);
        }
        $company = Company::create($input);
        return $this->sendResponse(new BaseResource($company), 'Company created successfully.');
    }

    public function show(Company $company): JsonResponse
    {
        return $this->sendResponse(new BaseResource($company), 'Company retrieved successfully.');
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $input = $request->validated();
        $input['slug'] = strtolower(str_replace(' ', '-', $input['name']));
        if (isset($input['logo']) && $input['logo']) {
            $input['logo'] = Company::STORAGE_PATH . time() . '.' . $request->logo->extension();
            $request->logo->storeAs(Company::STORAGE_PATH, $input['logo']);
        } else {
            unset($input['logo']);
        }
        $company->update($input);
        return $this->sendResponse(new BaseResource($company), 'Company updated successfully.');
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();
        return $this->sendResponse(new BaseResource($company), 'Company deleted successfully.');
    }
}
