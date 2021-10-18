<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'log_type', 'log_summary', 'icon_name','status'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
