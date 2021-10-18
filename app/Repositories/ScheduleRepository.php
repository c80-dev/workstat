<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Http\Resources\ScheduleResource;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public $model;

    public function __construct(Schedule $model)
    {
       $this->model = $model;
    }

    //create schedules
    public function createSchedule($request)
    {
        $validator =  Validator::make($request->all(),[
            'title' => 'required',
            'week_days'   => 'required',
            'schedule_in' => 'required',
            'schedule_out' => 'required'
        ]);

        if ($validator->fails()) {
              return response()->json([
                  'status_code' => 422,
                  'message' => $validator->messages()->first()
              ], 422);
        }else {

          try {
            $this->model->create([
                'title' => $request->title,
                'week_days' => json_encode($request->week_days),
                'schedule_in' => $request->schedule_in,
                'schedule_out' => $request->schedule_out,
                'organization_id' => auth()->user()->organization_id,
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Schedule created successfully'
            ], 200);
          } catch (\Exception $e) {

              return response()->json([
                  'status_code' => 400,
                  'message' => 'Sorry unable to create schedule'
              ], 400);
          }
        }
    }

    //edit schedule
    public function updateSchedule($request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'title' => 'sometimes',
            'week_days'   => 'sometimes',
            'schedule_in' => 'sometimes',
            'schedule_out' => 'sometimes'
        ]);

        if ($validator->fails()) {
                return response()->json([
                    'status_code' => 422,
                    'message' => $validator->messages()->first()
                ], 422);
        }else {

            $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {
                $schedule = $this->model->find($id);
                try {
                    $schedule->update([
                        'title' =>  empty($request->title) ? $schedule->title : $request->title,
                        'week_days' =>  empty($request->week_days) ? $schedule->week_days : json_encode($request->week_days),
                        'schedule_in' => empty($request->schedule_in) ? $schedule->schedule_in : $request->schedule_in,
                        'schedule_out' => empty($request->schedule_out) ? $schedule->schedule_out : $request->schedule_out,
                        'organization_id' => auth()->user()->organization_id,
                    ]);
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Schedule updated successfully'
                    ], 200);
                } catch (\Exception $e) {

                    return response()->json([
                        'status_code' => 422,
                        'message' => 'Sorry unable to update schedule'
                    ], 422);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this schedule  do not exist'
                ], 400);
            }
        }
    }

    //all schedules
    public function allSchedules()
    {
        $schedules =  $this->model->where('organization_id', '=', auth()->user()->organization_id)->latest()->paginate(10);
        if (count($schedules) > 0) {
           return ScheduleResource::collection($schedules);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry no record was found'
            ], 400);
        }
    }

    //show schedule
    public function showByID($id)
    {
        $data = $this->model->with('organization')->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->get();
        if (count($data) > 0) {
            $schedule = $this->model->find($id);
            return new ScheduleResource($schedule);
        }else {
          return response()->json([
              'status_code' => 422,
              'message' => 'Sorry this schedule do not exist'
          ], 422);
        }
    }

    //delete schedule
    public function deleteSchedule($id)
    {
          $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {
                try {
                    $schedule = $this->model->find($id)->delete();
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Schedule deleted successfully'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'Sorry unable to delete schedule'
                    ], 422);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this schedule do not exist'
                ], 400);
            }
    }
}
