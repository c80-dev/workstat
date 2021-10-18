<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    protected $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->middleware('auth:api');
    }
    
    /**
     * @OA\Get(
     *      path="/departments",
     *      operationId="AllDepartments",
     *      tags={"Show All Departments"},
     *      description="Show all departments",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Sorry no record was found",
     *      ),
     *     )
    */
    public function index()
    {
        $departments = $this->departmentRepository->allDepartment();
        return $departments;
    }

    /**
     * @OA\Post(
     *      path="/departments",
     *      operationId="CreateDepartment",
     *      tags={"Create department"},
     *      description="Create  department",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Enter department name",
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Enter department description",
     *     ),
     *   @OA\Parameter(
     *         name="parent_id",
     *         in="query",
     *         description="Enter department  parent_id",
     *     ),
     *  @OA\Parameter(
     *         name="hod_id",
     *         in="query",
     *         description="Enter department hod_id",
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Department created successfully",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Sorry unable to create department",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *     )
   */
    public function store(Request $request)
    {
        $department = $this->departmentRepository->createDepartment($request);
        return $department;
    }

     /**
   * @OA\Get(
   *      path="/departments/{id}",
   *      operationId="ShowDepartment",
   *      tags={"Show Department"},
   *      description="Show single department",
   *     @OA\Parameter(
   *         name="id",
   *         in="query",
   *         description="Enter department id",
   *         required=true,
   *     ),
   *      @OA\Response(
   *          response=200,
   *          description="Successful operation",
   *       ),
   *      @OA\Response(
   *          response=400,
   *          description="Sorry this record do not exist",
   *      ),
   *     )
   */
    public function show($id)
    {
        $department = $this->departmentRepository->showDepartment($id);
        return $department;
    }

    /**
   * @OA\Patch(
   *      path="/departments/{id}",
   *      operationId="UpdateDepartment",
   *      tags={"Update department"},
   *      description="Update  department",
   *     @OA\Parameter(
   *         name="id",
   *         in="query",
   *         description="Enter id",
   *     ),
   *     @OA\Parameter(
   *         name="name",
   *         in="query",
   *         description="Enter department name",
   *     ),
   *     @OA\Parameter(
   *         name="description",
   *         in="query",
   *         description="Enter department description",
   *     ),
   *   @OA\Parameter(
   *         name="parent_id",
   *         in="query",
   *         description="Enter department  parent_id",
   *     ),
   *  @OA\Parameter(
   *         name="hod_id",
   *         in="query",
   *         description="Enter department hod_id",
   *     ),
   *      @OA\Response(
   *          response=200,
   *          description="Department updated successfully",
   *       ),
   *      @OA\Response(
   *          response=400,
   *          description="Sorry unable to update department",
   *      ),
   *      @OA\Response(
   *          response=404,
   *          description="Sorry this data do not exist",
   *      ),
   *      @OA\Response(
   *          response=422,
   *          description="Validation error",
   *      ),
   *     )
   */
    public function update(Request $request, $id)
    {
        $department = $this->departmentRepository->updateDepartment($request, $id);
        return $department;
    }

    /**
    * @OA\Delete(
    *      path="/departments/{id}",
    *      operationId="deleteDepartment",
    *      tags={"Delete Department"},
    *      summary="Delete existing department",
    *      description="Deletes a department and returns no content",
    *      @OA\Parameter(
    *          name="id",
    *          description="Department Id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Response(
    *          response=204,
    *          description="Successful operation",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Sorry this records do not exist"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Sorry unable to delete department"
    *      ),
    * )
    */
    public function destroy($id)
    {
        $department = $this->departmentRepository->deleteDepartment($id);
        return $department;
    }
}
