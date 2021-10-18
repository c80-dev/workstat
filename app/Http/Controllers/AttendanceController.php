<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function page()
    {
        $queryDate = Carbon::createFromFormat('Y-m-d', '2021-05-02');
        $recrods = Attendance::with(['employee'])->has('employee')->whereDate('auth_date', '=', $queryDate)->get();
        return view('welcome')->with(['records' => $recrods]);
    }

    public function checkAttendance(Request $request)
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
            try {
                $queryDate = Carbon::createFromFormat('Y-m-d', $request->input('date'));
                $recrods = Attendance::with(['employee'])->has('employee')->whereDate('auth_date', '=', $queryDate)->get();
                return response()->json([
                    'records' =>  $recrods
                ]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
