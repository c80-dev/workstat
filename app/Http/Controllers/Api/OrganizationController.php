<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class OrganizationController extends Controller
{
    private $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

     /**
     * @OA\Get(
     *      path="/organizations",
     *      operationId="GetOrganizations",
     *      tags={"organizations"},
     *      summary="Get list of all organizations",
     *      description="Returns list of organizations",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index()
    {
        $organizations = $this->organizationRepository->allOrganizations();
        return $organizations;
    }

    /**
     * @OA\Post(
     *      path="/organization-create",
     *      operationId="postOrganization",
     *      tags={"Organization"},
     *      summary="Organization create",
     *      description="Organization create",
     *      @OA\Response(
     *          response=200,
     *          description="Organization created successfully",
     *       ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="domain",
     *         in="query",
     *         description="organization domain",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="office logo",
     *         required=true,
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *      ),
     *     )
     */

    public function store(Request $request)
    {
        return $this->organizationRepository->createOrganization($request);
    }

     /**
     * @OA\Get(
     *      path="/organization-show",
     *      operationId="getOrganizationDetails",
     *      tags={"Organization"},
     *      summary="Get organization details",
     *      description="Returns organization details",
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Json",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function show($id)
    {
       $organization = $this->organizationRepository->showByID($id);
       return  $organization;
    }

    /**
     * @OA\Patch(
     *      path="/organization-update",
     *      operationId="updateOrganization",
     *      tags={"Organization"},
     *      summary="Update Organization records",
     *      description="Update Organization records",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Name",
     *     ),
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *     ),
     *     @OA\Parameter(
     *         name="domain",
     *         in="query",
     *         description="organization domain",
     *     ),
     *     @OA\Parameter(
     *         name="image_path",
     *         in="query",
     *         description="office logo",
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *      ),
     *     )
     */
    public  function update(Request $request, $id)
    {
          $data = $this->organizationRepository->updateOrganization($request, $id);
          return $data;
    }

    /**
     * @OA\Delete(
     *      path="/organization-delete",
     *      operationId="deleteOrganization",
     *      tags={"Organization"},
     *      summary="Delete organization records",
     *      description="Delete organization records",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      ),
     *     )
     */
    public function destroy($id)
    {
       return $this->organizationRepository->deleteOrganization($id);
    }


}
