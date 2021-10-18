<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleController extends Controller
{
    private $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->middleware('auth:api');
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * @OA\Get(
     *      path="/schedules",
     *      operationId="GetSchedules",
     *      tags={"Schedules"},
     *      summary="Get list of all Schedules",
     *      description="Returns list of Schedules",
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
        $schedules = $this->scheduleRepository->allSchedules();
        return $schedules;
    }

    /**
     * @OA\Post(
     *      path="/schedule-create",
     *      operationId="postSchedule",
     *      tags={"Schedule"},
     *      summary="Register",
     *      description="Register Schedule",
     *      @OA\Response(
     *          response=200,
     *          description="Schedule created successfully",
     *       ),
     *      @OA\Parameter(
     *         name="Title",
     *         in="query",
     *         description="Title",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="week_days",
     *         in="query",
     *         description="Week Days Json",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="schedule_in",
     *         in="query",
     *         description="Schedule start time",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="schedule_out",
     *         in="query",
     *         description="Schedule work time",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *      ),
     *     )
     */

    public function store(Request $request)
    {
        return $this->scheduleRepository->createSchedule($request);
    }

    /**
     * @OA\Get(
     *      path="/schedule-show",
     *      operationId="getSchedule",
     *      tags={"Employee"},
     *      summary="Get schedule",
     *      description="Returns schedule",
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
       $schedule = $this->scheduleRepository->showByID($id);
       return  $schedule;
    }

    /**
     * @OA\Post(
     *      path="/schedule-update",
     *      operationId="postSchedule",
     *      tags={"Schedule"},
     *      summary="Register",
     *      description="Update Schedule",
     *      @OA\Response(
     *          response=200,
     *          description="Schedule updated successfully",
     *       ),
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="Title",
     *         in="query",
     *         description="Title",
     *     ),
     *      @OA\Parameter(
     *         name="week_days",
     *         in="query",
     *         description="Week Days Json",
     *     ),
     *     @OA\Parameter(
     *         name="schedule_in",
     *         in="query",
     *         description="Schedule start time",
     *     ),
     *     @OA\Parameter(
     *         name="schedule_out",
     *         in="query",
     *         description="Schedule work time",
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *      ),
     *     )
     */

    public function update(Request $request, $id)
    {
        $data = $this->scheduleRepository->updateSchedule($request, $id);
        return $data;
    }

        /**
     * @OA\Delete(
     *      path="/schedule-delete",
     *      operationId="deleteSchedule",
     *      tags={"Schedule"},
     *      summary="Delete Schedule",
     *      description="Delete Schedul",
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
       return $this->ScheduleRepository->deleteSchedule($id);
    }
}
