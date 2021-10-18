<?php 

namespace App\Repositories\Contracts;

interface HolidayRepositoryInterface
{
    public function createHoliday($request);

    public function allHolidays();
}