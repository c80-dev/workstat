<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements WithHeadings
{
     /**
     * @return array|string[]
     * headers
     */
    public function headings(): array
    {
        return [
            'EMPLOYEE ID NUMBER',
            'NAME',
            'EMAIL',
            'DEPARTMENT',
            'GENDER',
            'DESIGNATION',
            'PHONE NUMBER',
            'APPOINTMENT DATE (year-month-day)',
            'TERMINATION DATE (year-month-day)',
            'CARD NO'
        ];
    }
}
