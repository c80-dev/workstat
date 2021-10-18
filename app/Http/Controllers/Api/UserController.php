<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * @OA\Patch(
     *      path="/password-reset",
     *      operationId="updatePassword Reset",
     *      tags={"Password Reset"},
     *      summary="Update Password Reset records",
     *      description="Update Password Reset records",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
     *         required=true,
     *     ),
     *      @OA\Parameter(
     *         name="old_password",
     *         in="query",
     *         description="Enter old password",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Enter new password",
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Enter new password confirmation",
     *     ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *      )
     *     )
     */
    public function update(Request $request)
    {
        $data = $this->userRepository->resetPassword($request);
        return $data;
    }
}
