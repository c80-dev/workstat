<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AttendanceRepositoryInterface;

class AttendanceController extends Controller
{
    private $attendanceRepository;

    /**
     * constructor function implementing attendance Repository and the auth middleware
     */
    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->middleware('auth:api');
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * @OA\Post(
     *      path="/attendance",
     *      operationId="CreateAttendance",
     *      tags={"Attendance Store"},
     *      summary="Create Employee Attendance",
     *      description="Create Employee Attendance",
     *     @OA\Parameter(
     *         name="employee_id",
     *         in="query",
     *         description="Employee id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="auth_date",
     *         in="query",
     *         description="Authenticated date",
     *         required=true,
     *     ),
     *    @OA\Parameter(
     *         name="clock_in",
     *         in="query",
     *         description="Clock in time",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="clock_out",
     *         in="query",
     *         description="Clock out time",
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Attendance marked successfully",
     *       )
     *     )
     */

    public function store(Request $request)
    {
        return $this->attendanceRepository->clockIn($request);
    }

    /**
     * @OA\Post(
     *      path="/daily-attendance",
     *      operationId="GetDailyAttendance",
     *      tags={"Daily Attendance"},
     *      summary="Get employee daily attendace",
     *      description="Get employee daily attendace",
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="date",
     *         required=false,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       )
     *     )
     */
    public function attendanceDaily(Request $request)
    {
        $attendance = $this->attendanceRepository->dailyAttendance($request);
        return $attendance;
    }

    /**
     * @OA\Post(
     *      path="/range-attendance",
     *      operationId="GetDailyAttendance",
     *      tags={"Daily Attendance"},
     *      summary="Get employee daily attendace",
     *      description="Get employee daily attendace",
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="date from",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="date toe",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       )
     *     )
     */
    public function attendanceRange(Request $request)
    {
        $attendance = $this->attendanceRepository->rangeAttendance($request);
        return $attendance;
    }

}
