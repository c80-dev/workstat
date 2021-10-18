<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function toArray($request)
    {
        $general_helper = new General();
        return [
            'id' => $this->id,
            'employee_id' => $general_helper->clean($this->employee_id),
            'name'    => $general_helper->clean($this->name),
            'email'    => $general_helper->clean($this->email),
            'gender'    => $general_helper->clean($this->gender),
            'designation'  => $general_helper->clean($this->designation),
            'phone'   => $general_helper->clean($this->phone),
            'image_path'  => $general_helper->clean($this->image_path),
            'organization_id'  => $general_helper->clean($this->organization_id),
            'effective_time'  => $general_helper->clean($this->effective_time),
            'expiry_time'  => $general_helper->clean($this->expiry_time),
            'card_no'   => $general_helper->clean($this->card_no),
            'address' => $general_helper->clean($this->address),
            'skills' =>  $this->skills,
            'department'  => new DepartmentResource($this->whenLoaded('department')),
            'attendances'   => AttendanceResource::collection($this->whenLoaded('attendances')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
