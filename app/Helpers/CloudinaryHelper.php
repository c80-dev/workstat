<?php 

namespace App\Helpers;

use JD\Cloudder\Facades\Cloudder;
use Illuminate\Http\Request;
use App\Models\Employee;

class CloudinaryHelper
{
    //image helper to upload to cloudinary 
    public function image_helper($request, $file)
    {
        if ($request->hasFile("$file")) {
            Cloudder::upload($request->file($file));
            $cloundary_upload = Cloudder::getResult();
            $image_url = $cloundary_upload['url'];
        }else {
            $image_url = "";
        }
        return $image_url;
    }

    //uploading images url from json
    public function batchUrlUpload(Request $request)
    {                          
      
        if ($request->isMethod('post')) {
            $employeeJson = $request->input();

            foreach (array_chunk($employeeJson['employees'],  ceil(count($employeeJson['employees'])/5)) as $chunk) {
                foreach ($chunk as $employee) {
                    $employees = Employee::where('employee_id', '=', $employee['emp_id'])->update([
                        'image_path' => $employee['secure_url']
                    ]);
                }
            }
        }
    }
}