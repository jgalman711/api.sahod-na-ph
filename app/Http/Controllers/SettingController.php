<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Resources\BaseResource;
use App\Models\Company;
use App\Models\Setting;
use App\Services\PeriodService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    protected $periodService;

    public function __construct(PeriodService $periodService)
    {
        $this->periodService = $periodService;
    }

    public function index(Company $company): JsonResponse
    {
        $settings = $company->setting;
        return $this->sendResponse(new BaseResource($settings), 'Company settings retrieved successfully.');
    }

    public function update(SettingRequest $request, Company $company): JsonResponse
    {
        $input = $request->validated();
        $companyPreviousPeriod = $company->periods()->latest()->first();
        if ($companyPreviousPeriod) {
            return $this->sendError('Existing period found. Please contact ES administrator.', 409);
        }
        $settings = Setting::updateOrCreate(
            ['company_id' => $company->id],
            $input
        );
        return $this->sendResponse(new BaseResource($settings), 'Company settings updated successfully.');
    }
}
