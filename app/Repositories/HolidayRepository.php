<?php 

namespace App\Repositories;

use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\HolidayRepositoryInterface;
use App\Http\Resources\HoliydayResource;

class HolidayRepository implements HolidayRepositoryInterface
{
    public $model;

    public function __construct(Holiday $model)
    {
        $this->model = $model;
    }

    //create holidays
    public function createHoliday($request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'date'   => 'required'
        ]);

        if ($validator->fails()) {
              return response()->json([
                  'status_code' => 422,
                  'message' => $validator->messages()->first()
              ], 422);
        }else {

          try {
            $this->model->create([
                'name' => $request->name,
                'date' => $request->date,
                'organization_id' => auth()->user()->organization_id,
            ]);
              return response()->json([
                  'status_code' => 200,
                  'message' => 'Holiday created successfully'
              ], 200);
          } catch (\Exception $e) {

              return response()->json([
                  'status_code' => 400,
                  'message' => 'Sorry unable to create holiday'
              ], 400);
          }
        }
    }

    //all holidays
    public function allHolidays()
    {
        $holidays =  $this->model->where('organization_id', '=', auth()->user()->organization_id)->latest()->paginate(10);
        if (count($holidays) > 0) {
           return HoliydayResource::collection($holidays);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry no record was found'
            ], 400);
        }
    }

}