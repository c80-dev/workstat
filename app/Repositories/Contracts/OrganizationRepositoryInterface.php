<?php 

namespace App\Repositories\Contracts;

interface OrganizationRepositoryInterface
{
    public function createOrganization($request);

    public function allOrganizations();

    public function showByID($id);

    public function updateOrganization($request, $id);

    public function updateHelper($request, $id);

    public function deleteOrganization($id);
}