<?php

namespace App\Repositories;

use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DepartmentResource;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    //create department
    public function createDepartment($request)
    {
            $validator =  Validator::make($request->all(),[
                'name' => 'required',
                'description' => 'required',
                'parent_id' => 'sometimes',
                'hod_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'message' => $validator->messages()->first()
                ], 422);
           }else {
                try {

                    $department = $this->model->create([
                        'name' => $request->name,
                        'description' => $request->description,
                        'parent_id' => $request->parent_id,
                        'hod_id' => $request->hod_id,
                        'organization_id' => auth()->user()->organization_id,
                    ]);
                    if ($department) {
                        return response()->json([
                            'status_code' => 200,
                            'id' => $department->id,
                            'message' => 'Departement created successfully'
                        ], 200);
                    }
                } catch (\Exception $e) {

                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry there was an error'
                    ], 400);
                }
           }

    }

    //all department 
    public function allDepartment()
    {
        $departments =  $this->model->where('organization_id', '=', auth()->user()->organization_id)->latest()->paginate(50);
        if (count($departments) > 0) {
           return DepartmentResource::collection($departments);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry no record was found'
            ], 400);
        }
    }

    //show single department
    public function showDepartment($id)
    {
        $data = $this->model->with(['hod','organization'])->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {
            $department = $this->model->with(['hod','organization'])->where('id', '=', $id)->first();
            return new DepartmentResource($department);
        }else {
          return response()->json([
              'status_code' => 400,
              'message' => 'Sorry this record do not exist'
          ], 400);
        }
    }

    //department update
    public function updateDepartment($request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'parent_id' => 'sometimes',
            'hod_id' => 'sometimes',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);

        } else {
            $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {

                $department = $this->model->find($id);
                $update = $department->update([
                    'name' => empty($request->name) ? $department->name : $request->name,
                    'description' =>  empty($request->description) ? $department->description : $request->description,
                    'parent_id' =>  empty($request->parent_id) ? $department->parent_id : $request->parent_id,
                    'hod_id' =>  empty($request->hod_id) ? $department->hod_id : $request->hod_id,
                ]);
                if ($update) {
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Department updated successfully'
                    ], 200);
                }else {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry unable to update department'
                    ], 400);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this record do not exist'
                ], 400);
            }
        }
    }

    //delete department
    public function deleteDepartment($id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {
            try {
                $department = $this->model->find($id)->delete();
                if ($department) {

                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Department deleted successfully'
                    ], 200);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Sorry unable to delete department'
                ], 401);
            }
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry this records do not exist'
            ], 400);
        }
    }
}