<?php

namespace App\Repositories\Contracts;

interface EmployeeRepositoryInterface
{
    public function createEmployee($request);

    public function allEmployee();
    
    public function showByID($id);

    public function updateEmployeeImage($request, $id);

    public function updateEmployee($request, $id);

    public function employeeJsonArray($request);

    public function updateHelper($request, $id);

    public function deleteEmployee($id);

    public function imageGetter($request);
}