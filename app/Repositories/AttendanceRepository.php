<?php

namespace App\Repositories;

use App\Models\Attendance;
use \Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Http\Resources\EmployeeResource;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public $model;
    public $model_schedule;
    public $model_employee;

    public function __construct(Attendance $model, Schedule $model_schedule, Employee $model_employee)
    {
        $this->model = $model;
        $this->model_schedule = $model_schedule;
        $this->model_employee = $model_employee;
    }

   //clock in attendance
    public function clockIn($request)
    {
        $validator =  Validator::make($request->all(),[
            'employee_id' => 'required',
            'auth_date'   => 'required',
            'clock_in'   => 'sometimes',
            'clock_out' => 'sometimes'
        ]);

        if ($validator->fails()) {
              return response()->json([
                  'status_code' => 422,
                  'message' => $validator->messages()->first()
              ], 422);
        }else {

                $schedule = $this->model_schedule->where('organization_id', '=', auth()->user()->organization_id)->first();

                $queryDate = Carbon::createFromFormat('Y-m-d', $request->auth_date);
                $todayAttendanceCheck = $this->model->where('employee_id', $request->employee_id)
                        ->whereDate('auth_date', '=', $queryDate)->exists();
                if (!$todayAttendanceCheck) {
                    try {

                        $this->model->create([
                            'employee_id' => $request->employee_id,
                            'auth_date' =>  $request->auth_date,
                            'clock_in' => $request->clock_in,
                            'schedule_in' => $schedule->schedule_in,
                            'schedule_out' =>  $schedule->schedule_out
                        ]);
                        return response()->json([
                            'status_code' => 200,
                            'message' => 'Attendance marked successfully'
                        ], 200);
                  } catch (\Exception $e) {

                      return response()->json([
                          'status_code' => 400,
                          'message' => 'Sorry unable to create attendance record'
                      ], 400);
                  }
                }else {
                    $todayAttendanceCheck = $this->model->where('employee_id', $request->employee_id)
                        ->whereDate('auth_date', '=', $queryDate)->update([
                        'clock_out' => $request->clock_out
                    ]);
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Employee clocked out successfully'
                    ], 200);
                }

        }
    }

    //daily attendance
    public function dailyAttendance($request)
    {
      $validator =  Validator::make($request->all(),[
          'date'   => 'sometimes'
      ]);

      if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);
      }else {

          $date = $request->input('date', date('Y-m-d'));

          try {
                  $queryDate = Carbon::createFromFormat('Y-m-d', $date);
                  $employeesAttendance = $this->model_employee->with(['attendances' => function ($query) use ($queryDate) {
                      $query->select(['id', 'employee_id',  'auth_date', 'clock_in', 'clock_out', 'schedule_in', 'schedule_out','device_origin', 'device_name', 'ip_address'])
                            ->whereDate('auth_date', '=', $queryDate);
                      }])->where('organization_id', '=', auth()->user()->organization_id)->get();

                  return EmployeeResource::collection($employeesAttendance);

          } catch (\Exception $e) {

              return response()->json([
                  'status_code' => 400,
                  'message' => 'Sorry no records found'
              ], 400);
          }
      }
    }

    //range attendance
    public function rangeAttendance($request)
    {

        $validator =  Validator::make($request->all(),[
            'from'   => 'required',
            'to'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);
        }else {

            try {
                    $employeesAttendance = $this->model_employee->with(['attendances' => function ($query) use ($request) {
                          $query->select(['id', 'employee_id',  'auth_date', 'clock_in', 'clock_out', 'schedule_in', 'schedule_out','device_origin', 'device_name', 'ip_address'])
                                ->whereBetween('auth_date', [$request->from, $request->to]);
                    }])->where('organization_id', '=', auth()->user()->organization_id)->get();

                    return $employeesAttendance;

            } catch (\Exception $e) {

                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry no records found'
                ], 400);
            }
        }
    }
}
