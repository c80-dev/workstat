<?php

  namespace App\Helpers;

  use App\Models\Employee;
  use App\Models\Attendance;
  use Illuminate\Http\Request;
  use \Carbon\Carbon;
  use App\Helpers\General;

  class SettingsHelper
  {

    private $general;

    public function __construct(General $general)
    {
        $this->general = $general;
    }

    //employee sync
    public function employeeSync(Request $request)
    {
        set_time_limit(0);
        $employee = Employee::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' =>  $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'department' => $request->department,
            'organization_id' => $request->organization_id,
            'effective_time' => $request->appointment_date,
            'expiry_time' => $request->termination_date,
            'card_no' => $request->card_no
        ]);
    }
    
    //attendance batch reloat 
    public function attendanceSync(Request $request)
    {
        set_time_limit(0);
        if ($request->isMethod('post')) {
            $attendanceJson = $request->input();

            $clean_array = [];
            foreach ($attendanceJson['attendances'] as $key => $value) {
               array_push($clean_array, [
                    'employee_id' =>  $value['employeeID'],
                    'auth_date' => $value['authDate'],
                    'auth_time' => $value['authTime'],
                    'device_origin' => $value['deviceSN'],
                    'device_name' =>  $value['deviceName']
               ]);
            }

            foreach (array_chunk($clean_array,  ceil(count($clean_array)/5)) as $chunk) {
                foreach ($chunk as $attendance_data) {
                    $todayAttendanceCheck = Attendance::where('employee_id', '=', $attendance_data['employee_id'])
                        ->whereDate('auth_date', '=', $attendance_data['auth_date'])->get();
                        if (count($todayAttendanceCheck) < 1) {
                            $attendance = Attendance::create([
                                'employee_id' => $attendance_data['employee_id'],
                                'auth_date' =>  $attendance_data['auth_date'],
                                'clock_in' => $attendance_data['auth_time'],
                                'device_origin' => $attendance_data['device_origin'],
                                'device_name' => $attendance_data['device_name'],
                                'schedule_in' => $attendance_data['auth_time'],
                                'schedule_out' => $attendance_data['auth_time']
                            ]);
                        } else {
                            $todayAttendanceCheck = Attendance::where('employee_id', $attendance_data['employee_id'])
                                ->whereDate('auth_date', '=', $attendance_data['auth_date'])->update([
                                    'clock_out' => $attendance_data['auth_time']
                            ]);
                        }
                }
            }
        }
    }

  }
