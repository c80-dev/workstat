<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'parent_id', 'hod_id', 'organization_id'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function hod() {
        return $this->belongsTo(Employee::class, 'hod_id', 'employee_id');
    }

    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function scopeParent($query)
    {
        return $query->where('organization_id', '=', auth()->user()->organization_id)->where('id', '=', $this->parent_id)->get();
    }
}
