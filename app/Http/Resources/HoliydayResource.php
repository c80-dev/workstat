<?php

namespace App\Http\Resources;

use App\Helpers\General;
use Illuminate\Http\Resources\Json\JsonResource;

class HoliydayResource extends JsonResource
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
            'date'    => $general_helper->clean($this->date),
            'organization'   => $this->whenLoaded('organization'),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
