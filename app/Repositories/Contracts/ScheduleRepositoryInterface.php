<?php 

namespace App\Repositories\Contracts;

interface ScheduleRepositoryInterface
{
    public function createSchedule($request);

    public function updateSchedule($request, $id);

    public function allSchedules();

    public function showByID($id);

    public function deleteSchedule($id);

}