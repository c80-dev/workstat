<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    //update users 
    public function updateUser($request, $id)
    {

        $validator =  Validator::make($request->all(),[
            'name' => 'sometimes',
            'email' => 'sometimes',
            'phone' => 'sometimes'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->first()
            ], 422);

        } else {
            if (!empty($request->email)) {
                if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'status_code' => 422,
                        'message' => "Email must be a valid email"
                    ], 422);
                }else {
                    return $this->updateHelper($request, $id);
                }
            }else {
                    return $this->updateHelper($request, $id);
            }
        }
    }

    //update user password
    public function resetPassword($request)
    {
        $validator =  Validator::make($request->all(),[
            'old_password' => 'sometimes',
            'password' => 'required|confirmed'
        ]);

        $data = $this->model->where('id', '=', auth()->user()->id)->where('organization_id', '=', auth()->user()->organization_id)->exists();

        if ($data) {
            if ($validator->fails()) {

                return response()->json([
                    'status_code' => 422,
                    'message' => $validator->messages()->first()
                ], 422);

            } else {

                $hashedPassword = auth()->user()->password;

                if (Hash::check($request->old_password , $hashedPassword)) {

                    if (!Hash::check($request->password , $hashedPassword)) {
                        
                        try {
                            $user = $this->model->find(auth()->user()->id);
                            
                            $user->update([
                                'password' => empty($request->password) ? $user->password : bcrypt($request->password),
                            ]);
                            return response()->json([
                                'status_code' => 200,
                                'message' => 'User password updated successfully'
                            ], 200);
        
                        }catch (\Exception $e) {
                            return response()->json([
                                'status_code' => 400,
                                'message' => 'Sorry the update process faild'
                            ], 400);
                        }

                    }else {
                        return response()->json([
                            'status_code' => 422,
                            'message' => 'Sorry new password can not be the old password!'
                        ], 422); 
                    }
                }else {
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'Sorry old password doesnt matched'
                    ], 422);
                }
            }
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry this user do not exist'
            ], 400);
        }
    }

    //update user helper
    public function updateHelper($request, $id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {

          try {
              $user = $this->model->find($id);

              $user->update([
                  'name' => empty($request->name) ? $user->name : $request->name,
                  'email' =>   empty($request->email) ? $user->email : $request->email,
                  'phone' =>  empty($request->phone) ? $user->phone : $request->phone
              ]);

                return response()->json([
                    'status_code' => 200,
                    'message' => 'User details updated successfully'
                ], 200);
          } catch (\Exception $e) {

              return response()->json([
                  'status_code' => 400,
                  'message' => 'Sorry the update process faild'
              ], 400);
          }
        }else {
          return response()->json([
              'status_code' => 400,
              'message' => 'Sorry this user do not exist'
          ], 400);
        }
    }

    //delete user
    public function deleteUser($id)
    {
          $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {
                try {
                    $user = $this->model->find($id)->delete();
                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Organization details deleted successfully'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry unable to delete Organization'
                    ], 400);
                }
            }else {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Sorry this organization do not exist'
                ], 400);
            }
    }
}
