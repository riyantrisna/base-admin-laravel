<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('user');

        return view('user.index', $data);
    }

    public function changePassword(Request $request)
    {
        $result["status"] = TRUE;
        $validation_text = "";
        $result['status_item'] = TRUE;
        $result['message_item']['old_password'] = "";
        $result['message_item']['new_password'] = "";
        $result['message_item']['re_new_password'] = "";

        if(empty($request->old_password)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['old_password'] = multi_lang('old_password')." ".multi_lang('required');
        }

        if(empty($request->new_password)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['new_password'] = multi_lang('new_password')." ".multi_lang('required');
        }

        if(empty($request->re_new_password)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['re_new_password'] = multi_lang('re_new_password')." ".multi_lang('required');
        }

        if(!empty($request->new_password) && !empty($request->re_new_password) && $request->new_password != $request->re_new_password){
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('new_password_and_re_new_password_not_match')."</li>";
        }

        $user = User::where('id', auth()->user()->id)->first();
        if(!empty($user)){
            $data = [
                'email' => $user->email,
                'password' => $request->old_password,
                'status' => 1
            ];

            if (!Auth::attempt($data) && !empty($request->old_password)) {
                $result["status_item"] = $result["status_item"] && FALSE;
                $result['message_item']['old_password'] = multi_lang('old_password_is_wrong');
            }
        }else{
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('user_not_found')."</li>";
        }

        if($result["status"] && $result["status_item"]){

            $user->password = Hash::make($request->new_password);
            $user->updated_at = date('Y-m-d H:i:s');

            if($user->save()){
                $result["status"] = TRUE;
                $result["message"] = multi_lang('success_change_password');
            } else {
                $result["status"] = FALSE;
                $result["message"] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                $result["message"].= '<li>'.multi_lang('failed_change_password').'</li>';
                $result["message"].= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>';
                $result["message"].= '</div>';
            }
        }else if(!empty($validation_text)){
            $result["status"] = FALSE;
            $result["message"] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $result["message"].= $validation_text;
            $result["message"].= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>';
            $result["message"].= '</div>';
        }

        return response()->json($result, 200);
    }
}
