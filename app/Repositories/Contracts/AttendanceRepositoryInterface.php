<?php

namespace App\Repositories\Contracts;

interface AttendanceRepositoryInterface
{
    public function clockIn($request);

    public function dailyAttendance($request);

    public function rangeAttendance($request);
}
