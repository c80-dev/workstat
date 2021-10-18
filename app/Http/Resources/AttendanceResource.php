<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'employee_id' => $general_helper->clean($this->employee_id),
            'auth_date'    => $this->auth_date,
            'clock_in'    => $this->clock_in,
            'clock_out'  => $this->clock_out,
            'schedule_in'  => $this->schedule_in,
            'schedule_out'  => $this->schedule_out,
            'clockin_status' => $general_helper->timeIntervalSettings($this->schedule_in, $this->clock_in ),
            'clockout_status' => $general_helper->timeOutTimeIntervalSettings($this->schedule_out,$this->clock_out),
            'device_origin' => $general_helper->clean($this->device_origin),
            'device_name' =>  $general_helper->clean($this->device_name),
            'ip_address' => $general_helper->clean($this->id_address),
        ];
    }
}
