<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('company');

        $data['path_company_logo'] = url(env('PATH_COMPANY_LOGO'))."/";

        $data['detail'] = Company::where('company_id', 1)->first();

        if(!empty($data['detail']->company_logo)){
            $type = pathinfo($data['path_company_logo'].$data['detail']->company_logo, PATHINFO_EXTENSION);
            $base_64_images = base64_encode(file_get_contents($data['path_company_logo'].$data['detail']->company_logo));
            $data['detail']->base_64_images = 'data:image/' . $type . ';base64,' .$base_64_images;
        }else{
            $data['detail']->base_64_images = '';
        }

        return view('company.index', $data);
    }

    public function doEdit(Request $request)
    {

        $result["status"] = TRUE;
        $upload['status'] = true;
        $validation_text = "";
        $result['status_item'] = TRUE;
        $result['message_item']['logo'] = "";

        $path_company_upload = env('PATH_COMPANY_LOGO')."/";

        if(!empty($request->file_image_value)){
            $max_size = '1024'; // in KB
            $type_allow = 'jpg|JPG|png|PNG|jpeg|JPEG|gif|GIF';
            $upload = upload_base64($request->file_image_value, $path_company_upload, $max_size, $type_allow);
        }

        if(!$upload['status']){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['logo'] = $upload['message'];
        }

        if($result["status_item"]){
            $company = Company::where('company_id', 1)->first();

            if(!empty($request->file_image_value)){
                $logo = !empty($upload['file']) ? $upload['file'] : NULL;
            }else if(empty($request->file_image_value) AND !empty($request->file_image_value_old)){
                @unlink($path_company_upload.$request->file_image_value_old);
                $logo = NULL;
            }

            $company->company_name = $request->name;
            $company->company_phone = $request->phone;
            $company->company_address = $request->address;
            $company->company_logo = $logo;
            $company->updated_at = date('Y-m-d H:i:s');
            $company->updated_by = auth()->user()->id;

            if($company->save()){
                $result["status"] = TRUE;
                $result["message"] = multi_lang('success_edit_data');
                if(!empty($request->file_image_value) AND !empty($request->file_image_value_old)){
                    @unlink($path_company_upload.$request->file_image_value_old);
                    $result['new_file'] = $upload['file'];
                }
            } else {
                $result["status"] = FALSE;
                $result["message"] = multi_lang('failed_edit_data');
            }
        }else{
            $result["status"] = FALSE;
        }

        return response()->json($result, 200);
    }
}
