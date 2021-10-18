<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\EmployeeRepositoryInterface;

class EmployeeController extends Controller
{
    private $employeeRepository;

    /**
     * constructor function implementing Employee Repository and the auth middleware
     */
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->middleware('auth:api');
       
    }

   /**
     * @OA\Get(
     *      path="/employees",
     *      operationId="GetEmployees",
     *      tags={"Employees"},
     *      summary="Get list of all employees",
     *      description="Returns list of employees",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index()
    {
        $employees = $this->employeeRepository->allEmployee();
        return $employees;
    }

    /**
     * @OA\Post(
     *      path="/employees-json",
     *      operationId="Onboard employees",
     *      tags={"Employees"},
     *      summary="Onboard employees",
     *      description="Onboard employees",
     *      @OA\Parameter(
     *         name="employees",
     *         in="query",
     *         description="Employee json array",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      )
     *     )
     */
    public function employessArray(Request $request)
    {
        return $this->employeeRepository->employeeJsonArray($request);
    }

     /**
     * @OA\Post(
     *      path="/employees-register",
     *      operationId="postEmployee",
     *      tags={"Employee"},
     *      summary="Register",
     *      description="Register employee",
     *      @OA\Response(
     *          response=200,
     *          description="Employee created successfully",
     *       ),
     *      @OA\Parameter(
     *         name="employee_id",
     *         in="query",
     *         description="Employee id",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Employee name",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Employee email address",
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Employee phone number",
     *     ),
     *     @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         description="Employee gender",
     *     ),
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="Employee department",
     *     ),
     *    @OA\Parameter(
     *         name="designation",
     *         in="query",
     *         description="Employee designation",
     *     ),
     *    @OA\Parameter(
     *         name="address",
     *         in="query",
     *         description="Employee address",
     *     ),
     *     @OA\Parameter(
     *         name="skills",
     *         in="query",
     *         description="Employee skill set",
     *     ),
     *     @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="Employee image_path",
     *     ),
     *     @OA\Parameter(
     *         name="appointment_date",
     *         in="query",
     *         description="Appointment date",
     *     ),
     *     @OA\Parameter(
     *         name="termination_date",
     *         in="query",
     *         description="Termination date",
     *     ),
     *     @OA\Parameter(
     *         name="card_no",
     *         in="query",
     *         description="Card number",
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *     )
     */
    public function store(Request $request)
    {
      return $this->employeeRepository->createEmployee($request);
    }

     /**
     * @OA\Post(
     *      path="/create-image",
     *      operationId="crete image path",
     *      tags={"Employee image path created"},
     *      summary="create image paht",
     *      description="create iamge path for employees",
     *    @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="image path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *     )
     */
    public function imageGetter(Request $request)
    {
        return $this->employeeRepository->imageGetter($request);
    }

     /**
     * @OA\Get(
     *      path="/employee-show",
     *      operationId="getEmployeeDetails",
     *      tags={"Employee"},
     *      summary="Get employee details",
     *      description="Returns employee details",
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function show($id)
    {
       $employee = $this->employeeRepository->showByID($id);
       return  $employee;
    }

     /**
     * @OA\Patch(
     *      path="/employee-update",
     *      operationId="updateEmployee",
     *      tags={"Employee"},
     *      summary="Update employee records",
     *      description="Update employee records",
     *      @OA\Parameter(
     *         name="employee_id",
     *         in="query",
     *         description="Employee id",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Employee name",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Employee email address",
     *     ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Employee phone number",
     *     ),
     *     @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         description="Employee gender",
     *     ),
     *     @OA\Parameter(
     *         name="department",
     *         in="query",
     *         description="Employee department",
     *     ),
     *    @OA\Parameter(
     *         name="designation",
     *         in="query",
     *         description="Employee designation",
     *     ),
     *     @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="Employee image_path",
     *     ),
     *     @OA\Parameter(
     *         name="appointment_date",
     *         in="query",
     *         description="Appointment date",
     *     ),
     *     @OA\Parameter(
     *         name="termination_date",
     *         in="query",
     *         description="Termination date",
     *     ),
     *     @OA\Parameter(
     *         name="card_no",
     *         in="query",
     *         description="Card number",
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      )
     *     )
     */
    public  function update(Request $request, $id)
    {
        $data = $this->employeeRepository->updateEmployee($request, $id);
        return $data;
    }

    /**
     * @OA\Patch(
     *      path="/profile-image",
     *      operationId="Add profile picture",
     *      tags={"Employee"},
     *      summary="Add profile picture",
     *      description="Add profile picture",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Employee id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="Employee id",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      )
     *     )
     */
    public function uploadImage(Request $request, $id)
    {
        $data = $this->employeeRepository->updateEmployeeImage($request, $id);
        return $data;
    }

    /**
     * @OA\Delete(
     *      path="/employee-delete",
     *      operationId="deleteEmployee",
     *      tags={"Employee"},
     *      summary="Delete employee records",
     *      description="Delete employee records",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),

     *     )
     */
    public function destroy($id)
    {
       return $this->employeeRepository->deleteEmployee($id);
    }
}
