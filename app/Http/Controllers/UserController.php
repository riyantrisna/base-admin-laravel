<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('user');

        return view('user.index', $data);
    }

    public function data(Request $request)
	{
        $keyword = $request->search['value'];
        $start = $request->post('start');
        $length = $request->post('length');

        $columns = array(
            1 => 'name',
            2 => 'email',
            3 => 'role',
            4 => 'last_login'
        );

        $order = $columns[$request->order[0]['column']];
        $dir = $request->order[0]['dir'];

		$list = User::select(
            'id',
            'name',
            'email',
            'role',
            'mgn.menugroupname_name AS role_name',
            'last_login'
        )
        ->leftJoin('menu_group AS mg', 'mg.menugroup_id', 'users.id')
        ->leftJoin('menu_group_name AS mgn', 'mgn.menugroupname_menugroup_id', 'mg.menugroup_id')
        ->where('mgn.menugroupname_lang_code', auth()->user()->lang_code);
        if(!empty($keyword)){
            $keyword = '%'.$keyword .'%';
            $query = $list->where(function($q) use($keyword) {
                $q->where('name', 'LIKE', $keyword)
                ->orWhere('email', 'LIKE', $keyword)
                ->orWhere('mgn.menugroupname_name', 'LIKE', $keyword)
                ->orWhere(DB::raw('DATE_FORMAT(last_login,"%d/%m/%Y %H:%i:%s")'), 'LIKE', $keyword)
                ;
            });
        }

        $count = count($list->get());

        if (isset($start) AND $start != '') {
            $list = $list->offset($start)->limit($length);
        }

        $list = $list->orderBy($order, $dir);
        $list = $list->get();

        $data = array();
        $no = $request->post('start') + 1;

        if(!empty($list)){
            foreach ($list as $key => $value) {
                $row = array();
                $row[] = $no;
                //add html for action
                $row[] = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Detail" onclick="detail(\''.$value->id.'\')"><i class="fas fa-search"></i></a>
                        <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit('."'".$value->id."'".')"><i class="fas fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="deletes('."'".$value->id."','".$value->name."'".')"><i class="fas fa-trash-alt"></i></a>';
                $row[] = $value->name;
                $row[] = $value->email;
                $row[] = $value->role_name;
                $row[] = !empty($value->last_login) ? date("d/m/Y H:i:s", strtotime($value->last_login)) : "";

                $data[] = $row;
                $no++;
            }
        }

        $response = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data,
        );

        return response()->json($response, 200);
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
            $result['message_item']['re_new_password'] = multi_lang('new_password_and_re_new_password_not_match');
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
