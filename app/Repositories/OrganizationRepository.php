<?php 

namespace App\Repositories;

use App\Models\Organization;
use App\Helpers\CloudinaryHelper;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Http\Resources\OrganizationResource;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public $model;
    public $cloudinary;
    public $user_model;

    public function __construct(Organization $model, CloudinaryHelper $cloudinary, User $user_model)
    {
        $this->model = $model;
        $this->cloudinary = $cloudinary;
        $this->user_model = $user_model;
    }

    //crete organization
    public function createOrganization($request)
    {
        
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'domain' => 'required',
            'image_path' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status_code' => 422,
                'message' => $validator->messages()->first()
            ], 422);

        } else {

            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status_code' => 422,
                    'message' => "Email must be a valid email"
                ], 422);
            }else {
                $password = 'password';
                try {
                    $org = $this->model->create([
                        'name' => $request->name,
                        'official_email' => $request->email,
                        'domain' => $request->domain,
                        'image_path' => $this->cloudinary->image_helper($request)
                    ]);
                    if ($org) {
                        $this->user_model->create([
                            'organization_id' => $org->id,
                            'email' => $request->email,
                            'password' => bcrypt($password)
                        ]);
                        return response()->json([
                            'status_code' => 200,
                            'message' => 'Organization created successfully'
                        ], 200);
                    }else {
                        $this->model->find($org->id)->forceDelete();
                        return response()->json([
                            'status_code' => 422,
                            'message' => 'Sorry unable create organization admin'
                        ], 422);
                    }
                    
                } catch (\Exception $e) {
                    
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Sorry unable create'
                    ], 400);
                } 
            }
        }
    }

    //get all organization
    public function allOrganizations()
    {
        $organizations =  $this->model->latest()->paginate(10);
        if ($organizations) {
           return OrganizationResource::collection($organizations);
        }else {
            return response()->json([
                'status_code' => 400,
                'message' => 'Sorry no record was found'
            ], 400);
        }
    }

    //show employee id
    public function showByID($id)
    {
        $data = $this->model->with(['users','employees'])->where('id', '=', $id)->get();
        if (count($data) > 0) {
            $organization = $this->model->find($id);
            return new OrganizationResource($organization);
        }else {
          return response()->json([
              'status_code' => 400,
              'message' => 'Sorry this user do not exist'
          ], 400);
        }
    }

    //update organization
    public function updateOrganization($request, $id)
    {
        
        $validator =  Validator::make($request->all(),[
            'name' => 'sometimes',
            'email' => 'sometimes',
            'domain' => 'sometimes',
            'image_path' => 'sometimes'
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

    //update helper
    public function updateHelper($request, $id)
    {
        $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
        if ($data) {

          try {
              $organization = $this->model->find($id);

                $organization->update([
                  'name' => empty($request->name) ? $organization->name : $request->name,
                  'official_email' =>   empty($request->official_email) ? $organization->official_email : $request->official_email,
                  'domain' =>  empty($request->domain) ? $organization->domain : $request->domain,
                  'image_path' =>  empty($request->image_path) ? $organization->image_path :  $this->cloudinary->image_helper($request)
                ]);

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Organization details updated successfully'
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
              'message' => 'Sorry this organization do not exist'
          ], 400);
        }
    }

    //delete 
    public function deleteOrganization($id)
    {
          $data = $this->model->where('id', '=', $id)->where('organization_id', '=', auth()->user()->organization_id)->exists();
            if ($data) {
                try {
                    $Organization = $this->model->find($id)->delete();
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