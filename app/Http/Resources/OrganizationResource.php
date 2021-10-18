<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'name' => $general_helper->clean($this->name),
            'official_email'    => $general_helper->clean($this->official_email),
            'domain'    => $general_helper->clean($this->domain),
            'image_path'    => $general_helper->clean($this->image_path),
            'site_administrators'   => $this->whenLoaded('users'),
            'employees'   => $this->whenLoaded('employees'),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
