<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //metrix
    public function dashboardMetrix()
    {
        $now = Carbon::now();
        $last_month = Carbon::now()->subMonth();
        $auth_id = auth()->user()->organization_id;

        $total_employees_count = Employee::where('organization_id', '=', $auth_id)->count();
        $male_employees_count = Employee::where('gender', '=', 'male')->where('organization_id', '=', $auth_id)->count();
        $female_employees_count = Employee::where('gender', '=', 'female')->where('organization_id', '=', $auth_id)->count();
        $department_count = Department::where('organization_id', '=', $auth_id)->count();
        $last_month_employees_count = Employee::whereRaw('MONTH(created_at) = '.$last_month->month)->where('organization_id', '=', $auth_id)->count();

        $today_attendance = Attendance::with(['employee' => function ($query) use ($auth_id) {
            $query->where('organization_id', '=', $auth_id);
        }])->whereDate('auth_date', '=', $now->day)->count();

        return response()->json([
            'status_code' => 200,
            'total_employees' =>  $total_employees_count,
            'male_employees'  =>  $male_employees_count,
            'female_employees' => $female_employees_count,
            'department' =>  $department_count,
            'last_mont_employees' => $last_month_employees_count,
            'today_attendance' =>  $today_attendance
        ], 200);

    }
}
