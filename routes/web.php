<?php

use Illuminate\Support\Facades\Route;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',  [App\Http\Controllers\AttendanceController::class, 'page'])->name('page');


Route::get('/batch', function () {
   return $emp = Excel::download(new EmployeeExport(), 'employees-template.xlsx');
});

Route::get('/sync-attendance', [App\Helpers\General::class, 'syncAttendance']);
