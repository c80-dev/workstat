<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'organization_id', 'title', 'week_days', 'schedule_in', 'schedule_out'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    protected $casts = [
        'week_days' => 'array'
    ];
}
