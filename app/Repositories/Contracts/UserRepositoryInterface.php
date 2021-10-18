<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function updateUser($request, $id);

    public function resetPassword($request);

    public function updateHelper($request, $id);

    public function deleteUser($id);
}