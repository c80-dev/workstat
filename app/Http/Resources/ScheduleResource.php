<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $general_helper = new General();
        return [
            'id' => $this->id,
            'title' => $general_helper->clean($this->title),
            'week_days'    => $this->week_days,
            'schedule_in'    => $general_helper->clean($this->schedule_in),
            'schedule_out'  => $general_helper->clean($this->schedule_out),
            'organization'   => $this->whenLoaded('organization'),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
