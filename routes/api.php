<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/search', [App\Http\Controllers\AttendanceController::class, 'checkAttendance'])->name('search');

Route::group(['middleware' => 'api', 'prefix' => 'v0.01'], function ($router) {

        Route::post('/upload-path', [App\Helpers\CloudinaryHelper::class, 'batchUrlUpload']);

        //settings endpoint
        Route::post('/employees-batch', [App\Helpers\SettingsHelper::class, 'employeeSync']);
        Route::post('/attendance-batch', [App\Helpers\SettingsHelper::class, 'attendanceSync']);

        Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
        Route::post('/register-organization', [App\Http\Controllers\Api\OrganizationController::class, 'store']);

        Route::group(['middleware' => ['jwt.verify']], function() {

              Route::get('/my-hods',  [App\Helpers\General::class, 'allMyEmployees']);
              Route::get('/dashboard',  [App\Helpers\Dashboard::class, 'dashboardMetrix']);

              Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
              Route::post('/refresh', [App\Http\Controllers\Api\AuthController::class, 'refresh']);
              Route::get('/user-profile', [App\Http\Controllers\Api\AuthController::class, 'userProfile']);
              //employees routes
              Route::post('/employees-register', [App\Http\Controllers\Api\EmployeeController::class, 'store']);
              Route::get('/employees', [App\Http\Controllers\Api\EmployeeController::class, 'index']);
              Route::get('/employee-show/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'show']);
              Route::patch('/employee-update/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'update']);
              Route::delete('/employee-delete/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'destroy']);
              Route::post('/employees-json', [App\Http\Controllers\Api\EmployeeController::class, 'employessArray']);
              Route::post('/create-image', [App\Http\Controllers\Api\EmployeeController::class, 'imageGetter']);
              Route::post('/profile-image/{id}', [App\Http\Controllers\Api\EmployeeController::class, 'uploadImage']);
              //attendance routes
              Route::post('/attendance', [App\Http\Controllers\Api\AttendanceController::class, 'store']);
              Route::post('/daily-attendance', [App\Http\Controllers\Api\AttendanceController::class, 'attendanceDaily']);
              Route::post('/range-attendance', [App\Http\Controllers\Api\AttendanceController::class, 'attendanceRange']);
              //schedule endpoint
              Route::post('/schedule-create', [App\Http\Controllers\Api\ScheduleController::class, 'store']);
              Route::get('/schedules', [App\Http\Controllers\Api\ScheduleController::class, 'index']);
              Route::get('/schedule-show/{id}', [App\Http\Controllers\Api\ScheduleController::class, 'show']);
              Route::patch('/schedule-update/{id}', [App\Http\Controllers\Api\ScheduleController::class, 'update']);
              Route::delete('/schedule-delete/{id}', [App\Http\Controllers\Api\ScheduleController::class, 'destroy']);
              //holiday endpoint
              Route::post('/holiday-create', [App\Http\Controllers\Api\HolidayController::class, 'store']);
              Route::get('/holidays', [App\Http\Controllers\Api\HolidayController::class, 'index']);
              //organization
              Route::post('/organization-update', [App\Http\Controllers\Api\OrganizationController::class, 'update']);
              Route::get('/organizations', [App\Http\Controllers\Api\OrganizationController::class, 'index']);
              //password-reset
              Route::post('/password-reset', [App\Http\Controllers\Api\UserController::class, 'update']);
              //log routes
              Route::get('/logs', [ App\Helpers\General::class, 'allLogs']);
              Route::get('/logs-count', [ App\Helpers\General::class, 'logCounts']);
              Route::patch('/view-logs', [ App\Helpers\General::class, 'updateLogs']);

              // V0.02 ROUTES
              Route::apiResource('/departments', App\Http\Controllers\Api\DepartmentController::class);

        });

});
