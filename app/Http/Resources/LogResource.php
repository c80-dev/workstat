<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
            'organization_id' => $general_helper->clean($this->organization_id),
            'log_type'    => $general_helper->clean($this->log_type),
            'log_summary'    => $general_helper->clean($this->log_summary),
            'icon_name'  => $general_helper->clean($this->icon_name),
            'status'  => $general_helper->clean($this->status),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
