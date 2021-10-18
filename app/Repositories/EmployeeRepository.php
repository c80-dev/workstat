<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Employee;
use App\Helpers\CloudinaryHelper;
use Illuminate\Support\Facades\Validator;
use App\Helpers\General;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Http\Resources\EmployeeResource;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public $model;
    public $cloudinary;
    public $general_helper;

    public function __construct(Employee $model, CloudinaryHelper $cloudinary, General $general_helper)
    {
        $this->model = $model;
        $this->cloudinary = $cloudinary;
        $this->general_helper = $general_helper;
    }

    //get image path
    public function imageGetter($request)
    {
        $validator =  Validator::make($request->all(),[
            'image_path' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);

        } else {
            return response()->json([
                'status_code' => 201,
                'image_path' => $this->cloudinary->image_helper($request, 'image_path')
            ], 201);
        }
    }

    //create employees
    public function createEmployee($request)
    {
        $validator =  Validator::make($request->all(),[
          'employee_id' => 'required|unique:employees',
          'name' => 'required',
          'email' => 'required|unique:employees',
          'phone' => 'required',
          'gender' => 'sometimes',
          'department_id' => 'sometimes',
          'designation' => 'sometimes',
          'image_path' => 'sometimes',
          'appointment_date' => 'sometimes',
          'skills' => 'sometimes',
          'address' => 'sometimes',
          'termination_date' => 'sometimes',
          'card_no'     => 'sometimes',
        ]);

        if ($validator->fails()) {
             return response()->json([
                 'status_code' => 422,
                 'message' => $validator->messages()->first()
             ], 422);
        }else {

            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status_code' => 422,
                    'message' => "Email must be a valid email"
                ], 422);
            }else {

                try {

                    $employee = $this->model->create([
                        'employee_id' => $request->employee_id,
                        'name' => $request->name,
                        'email' =>  $request->email,
                        'phone' => $request->phone,
                        'image_path' =>  $request->image_path,
                        'gender' => $request->gender,
                        'department_id' => $request->department_id,
                        'designation' => $request->designation,
                        'organization_id' => auth()->user()->organization_id,
                        'effective_time' => $request->appointment_date,
                        'expiry_time' => $request->termination_date,
                        'skills' => json_encode($request->skills),
                        'address' => $request->address,
                        'card_no' => $request->card_no
                    ]);
                    if ($employee) {

                        $logRequest = [
                            'log_type' => 'Employee Created',
                            'log_summary' => "You have successfully created an employee with name $request->name",
                            'icon_name' => 'User'
                        ];
                        $log =  $this->general_helper->createLog($logRequest);
                        return response()->json([
                            'status_code' => 200,
                            'id' => $employee->id,
                            'message' => 'Employee created successfully'
                        ], 200);
                    }
                } catch (\Exception $e) {

                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry there was an error in the registration process'
                    ], 400);
                }
            }
       }
    }

    //select all employees
    public function allEmployee()
    {
        $employees =  $this->model->with(['department'])->where('organization_id', '=', auth()->user()->organization_id)->latest()->paginate(50);
        if (count($employees) > 0) {
           return EmployeeResource::collection($employees);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry no record was found'
            ], 400);
        }
    }

    //show single employee
    public function showByID($id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->get();
        if (count($data) > 0) {
            $employee = $this->model->with(['attendances','department'])->find($id);
            return new EmployeeResource($employee);
        }else {
          return response()->json([
              'status_code' => 400,
              'message' => 'Sorry this user do not exist'
          ], 400);
        }
    }

    //employee image update
    public function updateEmployeeImage($request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'image_path' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);

        } else {
            $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {

            try {
                    $employee = $this->model->find($id);

                    $profile_update = $employee->update([
                        'image_path' =>  $this->cloudinary->image_helper($request, 'image_path')
                    ]);
                    if ($profile_update) {

                        $logRequest = [
                            'log_type' => 'Employee Profile Image Changed',
                            'log_summary' => "You have successfully changed an employee with name $employee->name",
                            'employee_id' => $employee->employee_id,
                            'icon_name' => 'Edit'
                        ];
                        $log =  $this->general_helper->createLog($logRequest);
                        return response()->json([
                            'status_code' => 200,
                            'message' => 'Employee profile image updated successfully'
                        ], 200);
                    }
            } catch (\Exception $e) {

                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry the update process faild'
                ], 400);
            }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this user do not exist'
                ], 400);
            }
        }
    }

    //ulpdate employee
    public function updateEmployee($request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'gender' => 'sometimes',
            'department_id' => 'sometimes',
            'designation' => 'sometimes',
            'image_path' => 'sometimes',
            'appointment_date' => 'sometimes',
            'termination_date' => 'sometimes',
            'address' => 'sometimes',
            'skills' => 'sometimes',
            'card_no'     => 'sometimes',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);

        } else {
            if (!empty($request->email)) {
                if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'status_code' => 422,
                        'message' => "Email must be a valid email"
                    ], 422);
                }else {
                    return $this->updateHelper($request, $id);
                }
            }else {
                    return $this->updateHelper($request, $id);
            }
        }
    }

    //json array upload
    public function employeeJsonArray($request)
    {
        if ($request->isMethod('post')) {
            $employeeJson = $request->input();

            $failed_messages = [];
            $passed_messages = [];

            $jsonRules = [
                'employee_id' => ['required', 'unique:employees'],
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:employees' ],
                'phone' => ['required', 'min:11', 'max:11', 'regex:/[0-9]{9}/', 'unique:employees'],
                'gender' => ['sometimes'],
                'department' => ['sometimes'],
                'designation' => ['sometimes'],
                'image_path' => ['sometimes'],
                'appointment_date' => ['sometimes', 'date'],
                'termination_date' => ['sometimes', 'date'],
                'card_no'     => ['sometimes']
            ];

            //clean up duplicate
            foreach ($employeeJson['employees'] as $current_key => $current_array) {
                foreach ($employeeJson['employees'] as $search_key => $search_array) {
                    if ($search_array['employee_id'] == $current_array['employee_id'] || $search_array['email'] == $current_array['email'] || $search_array['phone'] == $current_array['phone']) {
                        if ($search_key != $current_key) {
                            array_push($failed_messages,  [
                                'employee_id' =>  $search_array['employee_id'],
                                'name' => $search_array['name'],
                                'email' => $search_array['email'],
                                'phone' => $search_array['phone'],
                                'gender' => $search_array['gender'],
                                'department' => $search_array['department'],
                                'designation' => $search_array['designation'],
                                'appointment_date' => $search_array['appointment_date'],
                                'termination_date' => $search_array['termination_date'],
                                'card_no' => $search_array['card_no'],
                                'error_message' => array("Duplicate record detected, please check the Employee-ID, Email-Address and Phone-Number")
                            ]);
                            unset($employeeJson['employees'][$search_key]);
                        }
                    }
                }
            }

            foreach ($employeeJson['employees']  as $key => $employeeData){
                $validator = Validator::make($employeeData, $jsonRules);

                if ($validator->fails()) {
                    array_push($failed_messages, [
                        'employee_id' =>  $employeeData['employee_id'],
                        'name' => $employeeData['name'],
                        'email' => $employeeData['email'],
                        'phone' => $employeeData['phone'],
                        'gender' => $employeeData['gender'],
                        'department' => $employeeData['department'],
                        'designation' => $employeeData['designation'],
                        'appointment_date' => $employeeData['appointment_date'],
                        'termination_date' => $employeeData['termination_date'],
                        'card_no' => $employeeData['card_no'],
                        'error_message' => $validator->messages()->all()
                    ]);
                }elseif(!$validator->fails()) {
                    $department_check = $general_helper->checkDepartment($employeeData['department']);
                    if ($department_check) {
                        array_push($passed_messages, [
                            'employee_id' =>  $employeeData['employee_id'],
                            'name' => $employeeData['name'],
                            'email' => $employeeData['email'],
                            'phone' => $employeeData['phone'],
                            'gender' => $employeeData['gender'],
                            'department_id' => $department_check,
                            'designation' => $employeeData['designation'],
                            'appointment_date' => $employeeData['appointment_date'],
                            'termination_date' => $employeeData['termination_date'],
                            'card_no' => $employeeData['card_no'],
                        ]);
                    }
                    
                }
            }

            //count passed_message array
            if (count($passed_messages) > 0) {
                foreach (array_chunk($passed_messages,  ceil(count($passed_messages)/5)) as $chunk) {
                    foreach ($chunk as $employee) {
                        $employees = $this->model->create([
                            'employee_id' => $employee['employee_id'],
                            'name' => $employee['name'],
                            'email' =>  $employee['email'],
                            'phone' => $employee['phone'],
                            'gender' => $employee['gender'],
                            'department_id' => $employee['department_id'],
                            'designation' => $employee['designation'],
                            'organization_id' => auth()->user()->organization_id,
                            'effective_time' => Carbon::createFromFormat('Y-m-d', $employee['appointment_date']),
                            'expiry_time' => Carbon::createFromFormat('Y-m-d', $employee['termination_date']),
                            'card_no' => $employee['card_no']
                        ]);
                    }
                }
                $records = count($passed_messages);
                $logRequest = [
                    'log_type' => 'Employees Batch Upload',
                    'log_summary' => "You have successfully created $records employees in batch",
                    'icon_name' => 'Users'
                ];
                $log =  $this->general_helper->createLog($logRequest);
                return response()->json([
                    'status_code' => 200,
                    'successful_rows' =>  $this->model->recent()->toArray(),
                    'faild_rows' => $failed_messages,
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 422,
                    'successful_rows' =>  $passed_messages,
                    'faild_rows' => $failed_messages
                ], 422);
            }
        }
    }

    //update helper
    public function updateHelper($request, $id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {

          try {
              $employee = $this->model->find($id);

                $updateEmployeeDetail = $employee->update([
                  'name' => empty($request->name) ? $employee->name : $request->name,
                  'email' =>   empty($request->email) ? $employee->email : $request->email,
                  'phone' =>  empty($request->phone) ? $employee->phone : $request->phone,
                  'image_path' =>  empty($request->image_path) ? $employee->image_path : $request->image_path,
                  'gender' => empty($request->gender ) ? $employee->gender : $request->gender,
                  'department_id' =>  empty($request->departments_id) ? $employee->department_id : $request->department_id,
                  'designation' =>  empty($request->designation) ? $employee->designation : $request->designation,
                  'effective_time' =>  empty($request->appointment_date) ? $employee->appointment_date : $request->appointment_date,
                  'expiry_time' =>  empty($request->termination_date) ? $employee->termination_date : $request->termination_date,
                  'address' => empty($request->address) ? $employee->address : $request->address,
                  'skills' => empty($request->skills) ? $employee->skills : json_encode($request->skills),
                  'card_no' => empty( $request->card_no) ? $employee->card_no :  $request->card_no,
                ]);
                if ($updateEmployeeDetail) {

                    $logRequest = [
                        'log_type' => 'Employee Profile Updated',
                        'log_summary' => "You have successfully updated an employee $employee->name",
                        'icon_name' => 'Edit'
                    ];
                    $log =  $this->general_helper->createLog($logRequest);
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Employee details updated successfully'
                    ], 200);
                }
          } catch (\Exception $e) {

              return response()->json([
                  'status_code' => 400,
                  'message' => 'Sorry the update process faild'
              ], 400);
          }
        }else {
          return response()->json([
              'status_code' => 400,
              'message' => 'Sorry this user do not exist'
          ], 400);
        }
    }

    //delete employee
    public function deleteEmployee($id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {
                try {
                    $employee = $this->model->find($id);
                    $employeeRemove = $employee->delete();
                    if ($employeeRemove) {

                        $logRequest = [
                            'log_type' => 'Deleted Employee',
                            'log_summary' => "You have successfully deleted an employee with name $employee->name",
                            'icon_name' => 'Trash2'
                        ];
                        $log =  $this->general_helper->createLog($logRequest);
                        return response()->json([
                            'status_code' => 200,
                            'message' => 'Employee details deleted successfully'
                        ], 200);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry unable to delete employee'
                    ], 400);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this user do not exist'
                ], 400);
            }
    }
}
