<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PeriodsController;
use App\Http\Controllers\SalaryComputationController;
use App\Http\Controllers\TimeRecordController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('reset-password', [PasswordResetController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum', ['role:super-admin', 'employee-of-company']], function () {
    Route::resource('companies', CompanyController::class);
    Route::group(['middleware' => ['role:business-admin']], function () {
        Route::get('{company}/dashboard', [DashboardController::class, 'dashboard']);
        Route::resource('companies', CompanyController::class)->only('view', 'update');
        Route::resource('companies.employees', EmployeeController::class);
        Route::resource('companies.work-schedules', WorkScheduleController::class);
        Route::resource('companies.payrolls', PayrollController::class);
        Route::resource('companies.employees.leaves', LeaveController::class);
        Route::prefix('companies/{company}/employees/{employee}')->group(function () {
            Route::get('/time-record', [TimeRecordController::class, 'getTimeRecords']);
            Route::post('/clock', [TimeRecordController::class, 'clock']);
            Route::prefix('salary-computation')->group(function () {
                Route::get('/', [SalaryComputationController::class, 'show']);
                Route::post('/', [SalaryComputationController::class, 'store']);
                Route::put('/', [SalaryComputationController::class, 'update']);
                Route::delete('/', [SalaryComputationController::class, 'delete']);
            });
            Route::prefix('/work-schedule')->group(function () {
                Route::get('/', [EmployeeScheduleController::class, 'show']);
                Route::post('/', [EmployeeScheduleController::class, 'store']);
                Route::put('/', [EmployeeScheduleController::class, 'update']);
                Route::delete('/', [EmployeeScheduleController::class, 'delete']);
            });
        });
        Route::resource('companies.payroll-periods', PeriodsController::class);
    });
});

