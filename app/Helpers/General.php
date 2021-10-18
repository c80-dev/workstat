<?php

namespace App\Helpers;

use App\Models\Log;
use App\Http\Resources\LogResource;
use App\Models\Department;
use App\Models\Employee;

class General
{

    //check in time
    public function timeIntervalSettings($scheduleIn,$checkIn)
    {
        $checkIn = strtotime($checkIn);
        $scheduleIn = strtotime($scheduleIn);
        $diff = $scheduleIn - $checkIn;
        return ($diff < 0)? 'Late' : 'Clocked In';
    }

    //check out  time
    public function timeOutTimeIntervalSettings($scheduleOut,$checkOut)
    {
        $checkOut = strtotime($checkOut);
        $scheduleOut = strtotime($scheduleOut);
        $diff = $scheduleOut - $checkOut;
       return ($diff < 0)? 'Clocked Out' : 'Early' ;
    }

    //clean null string
    public function clean($string)
    {
        if (is_null($string)) {
            return "";
        }else {
            return $string;
        }
    }

    //create logs
    public function createLog(array $request)
    {
        $create_log = Log::create([
            'organization_id' => auth()->user()->organization_id,
            'log_type' => $request['log_type'],
            'log_summary' => $request['log_summary'],
            'icon_name' => $request['icon_name'],
            'status' => ''
        ]);
        if ($create_log) {
            return true;
        }else {
            return false;
        }
    }

    //count logs
    public function logCounts()
    {
        $my_logs = Log::where('organization_id', '=', auth()->user()->organization_id)->where('status', '=', '')->count();
        return $my_logs;
    }

    //view all logs
    public function allLogs()
    {
        $my_logs = Log::where('organization_id', '=', auth()->user()->organization_id)->orderBy('created_at', 'desc')->get();
        return LogResource::collection($my_logs);
    }

    //update users logs
    public function updateLogs()
    {
        $my_logs_seen = Log::where('organization_id', '=', auth()->user()->organization_id)->where('status', '=', '')->update([
            'status' => 'seen'
        ]);
        if ($my_logs_seen) {
            return true;
        }else {
            return false;
        }
    }

    //check department
    public function checkDepartment($eepartment)
    {
        $department_id = '';
        $data = Department::where('name', '=', $department)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {
                $department = Department::where('name', '=', $department)->where('organization_id', '=', auth()->user()->organization_id)->first();
                $department_id = $department->id;
                return $department_id;
        }else {
            return $department_id;
        }
    }

    public function allMyEmployees()
    {
        return Employee::with(['attendances','department'])->where('organization_id', '=', auth()->user()->organization_id)->get();
    }
}
