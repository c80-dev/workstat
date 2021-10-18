<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
use JWTAuth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

     /**
     * @OA\Post(
     *      path="/login",
     *      operationId="authnticateAdmin",
     *      tags={"Authnticate"},
     *      description="Admin login",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Administrator email address",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Administrator password",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
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
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token =  $this->createNewToken($token);
        if ($token) {
            return response()->json([
                'token' => $token
            ]);
        }
    }


     /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutAdmin",
     *      summary="Logout admin",
     *      description="logout admin",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="user token",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *       )
     *     )
     */
    public function logout(Request $request) {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }else {

          try {
               JWTAuth::invalidate($request->token);
               return response()->json([
                  'status' => 200,
                  'message' => 'User logged out successfully.'
               ], 200);
           } catch (JWTException $e) {
               return response()->json([
                  'message' => 'Failed to logout, please try again.'
              ], 500);
           }
        }
    }

    /**
     * @OA\Post(
     *      path="/refresh",
     *      operationId="refreshToken",
     *      tags={"Token"},
     *      summary="Refresh Token",
     *      description="Returns new token",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     )
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @OA\Get(
     *      path="/user-profile",
     *      operationId="userPorfile",
     *      tags={"Profile"},
     *      summary="Get admin profile",
     *      description="Returns admin profile",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *     )
     */
    public function userProfile() {
        return response()->json([
            'organization_name' => auth()->user()->organization->name,
            'official_email' => auth()->user()->organization->offcial_email,
            'domain' => auth()->user()->organization->domain,
            'logo_path' => auth()->user()->organization->image_path
        ], 200);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
