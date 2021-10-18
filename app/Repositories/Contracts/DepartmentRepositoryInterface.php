<?php

namespace App\Repositories\Contracts;

interface DepartmentRepositoryInterface
{
    public function createDepartment($request);

    public function allDepartment();

    public function showDepartment($id);

    public function updateDepartment($request, $id);

    public function deleteDepartment($id);
}