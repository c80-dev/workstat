<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id','name', 'email', 'department_id', 'gender','designation','phone', 'image_path', 'organization_id', 'effective_time', 'expiry_time', 'card_no'
        ,'address', 'skills'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function scopeRecent($query) {
        return $query->where('organization_id', '=', auth()->user()->organization_id)->whereDate('created_at' , '=', Carbon::today())
            ->whereTime('created_at' , '>', Carbon::now()->subMinutes(5))->get();
    }

    protected $casts = [
        'skills' => 'array'
    ];

  }
