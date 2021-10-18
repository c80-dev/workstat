<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\HolidayRepositoryInterface;

class HolidayController extends Controller
{
    private $holidayRepository;

    public function __construct(HolidayRepositoryInterface $holidayRepository)
    {
        $this->middleware('auth:api');
        $this->holidayRepository = $holidayRepository;
    }

     /**
     * @OA\Get(
     *      path="/holidays",
     *      operationId="GetHolidays",
     *      tags={"Holidays"},
     *      summary="Get list of all Holidays",
     *      description="Returns list of Holidays",
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
        $holidays = $this->holidayRepository->allHolidays();
        return $holidays;
    }

    /**
     * @OA\Post(
     *      path="/holiday-create",
     *      operationId="postSchedule",
     *      tags={"Schedule"},
     *      summary="Holiday create",
     *      description="Holiday create",
     *      @OA\Response(
     *          response=200,
     *          description="Holiday created successfully",
     *       ),
     *      @OA\Parameter(
     *         name="Name",
     *         in="query",
     *         description="Name",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="date",
     *         required=true,
     *     ),
     *     @OA\Response(
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
        return $this->holidayRepository->createHoliday($request);
    }
}
