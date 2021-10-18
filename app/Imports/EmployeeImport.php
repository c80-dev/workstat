<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        return new Employee([
            'employee_id' => $row['employee_id'],
            'name' => $row['name'],
            'email' =>  $row['email'],
            'department' => $row['department'],
            'gender' =>  $row['gender'],
            'designation' =>  $row['designation'],
            'phone' => $row['phone_number'],
            'effective_time' => Carbon::parse($row['appointment_date'])->format('Y-m-d'),
            'expiry_time' =>  Carbon::parse($row['termination_date'])->format('Y-m-d'),
            'card_no' => $row['card_no'],
            'organization_id' => auth()->user()->organization_id
        ]);
    }

    public function rules(): array
    {
        return [
            '*.employee_id' => ['required','unique:employees,employee_id'],
            '*.name' => ['required'],
            '*.email' => ['required', 'email', 'unique:employees,email'],
            '*.department' => ['required'],
            '*.gender' => ['required'],
            '*.designation' => ['required'],
            '*.phone_number' => ['required','min:11','max:11','unique:employees,phone'],
            '*.appointment_date' => ['required'],
            '*.termination_date' => ['required'], 
            '*.card_no' => ['required']
        ];
    }

}
