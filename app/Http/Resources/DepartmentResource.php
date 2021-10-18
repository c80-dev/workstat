<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\General;

class DepartmentResource extends JsonResource
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
            'name'    => $general_helper->clean($this->name),
            'description'    => $general_helper->clean($this->description),
            'parent'  => $this->parent(),
            'hod'    => new EmployeeResource($this->whenLoaded('hod')),
            'organization'  => new  OrganizationResource($this->whenLoaded('organization')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
