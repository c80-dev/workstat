<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','official_email','domain','image_path'
    ];

    public function admins() {
        return $this->belongsToMany(User::class);
    }

    public function employees() {
        return $this->belongsToMany(Employee::class);
    }

    public function logs() {
        return $this->belongsToMany(Log::class);
    }
}
