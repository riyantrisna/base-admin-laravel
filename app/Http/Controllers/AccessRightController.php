<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Menu;
use App\Models\MenuGroup;
use App\Models\MenuGroupName;
use App\Models\Language;
use Illuminate\Support\Facades\Hash;

class AccessRightController extends Controller
{
    public function index()
    {
        $data['title'] = " - ".multi_lang('access_right');

        $language_master = Language::select(
            'lang_code AS id',
            'lang_name AS name'
        )->get();
        $data['language_master_json'] = json_encode($language_master, true);

        return view('access-right.index', $data);
    }

    public function data(Request $request)
	{
        $keyword = $request->search['value'];
        $start = $request->post('start');
        $length = $request->post('length');

        $columns = array(
            2 => 'mgn.menugroupname_name',
            3 => 'hmn.menuname_name'
        );

        $order = $columns[$request->order[0]['column']];
        $dir = $request->order[0]['dir'];

		$list = MenuGroup::select(
            'menugroup_id',
            'mgn.menugroupname_name AS menugroup_name',
            'menugroup_home_menu_id',
            'hmn.menuname_name AS menugroup_home_menu_name',
            'menugroup_menu_id'
        )
        ->leftJoin('menu_group_name AS mgn', function($join){
            $join->on('mgn.menugroupname_menugroup_id', 'menu_group.menugroup_id');
            $join->on('mgn.menugroupname_lang_code', DB::raw("'".auth()->user()->lang_code."'"));
        })
        ->leftJoin('menu AS hm', 'hm.menu_id', 'menu_group.menugroup_home_menu_id')
        ->leftJoin('menu_name AS hmn', function($join){
            $join->on('hmn.menuname_menu_id', 'hm.menu_id');
            $join->on('hmn.menuname_lang_code', DB::raw("'".auth()->user()->lang_code."'"));
        });
        if(!empty($keyword)){
            $keyword = '%'.$keyword .'%';
            $query = $list->where(function($q) use($keyword) {
                $q->where('mgn.menugroupname_name', 'LIKE', $keyword)
                ->orWhere('hmn.menuname_name', 'LIKE', $keyword)
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
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="deletes('."'".$value->id."','".$value->menugroup_name."'".')"><i class="fas fa-trash-alt"></i></a>';
                $row[] = $value->menugroup_name;
                $row[] = $value->menugroup_home_menu_name;

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

    public function add()
    {
        $data['language_master'] = Language::select(
            'lang_code AS id',
            'lang_name AS name'
        )->get();

        $data['home_menu_master'] = Menu::select(
            'menu_id AS id',
            'mn.menuname_name AS name'
        )
        ->leftJoin('menu_name AS mn', function($join){
            $join->on('mn.menuname_menu_id', 'menu.menu_id');
            $join->on('mn.menuname_lang_code', DB::raw("'".auth()->user()->lang_code."'"));
        })
        ->whereNotNull('menu_url')
        ->where('menu_status', 1)
        ->orderBy('menu_order', 'asc')
        ->get();

        $data['status_master'] = array(
            array('id' => 1, 'name' => multi_lang('active')),
            array('id' => 0, 'name' => multi_lang('not_active')),
        );

        $data['status_master'] = json_decode(json_encode($data['status_master']), FALSE);

        return view('access-right.add', $data);
    }

    public function doAdd(Request $request)
    {
        $result["status"] = TRUE;
        $validation_text = "";
        $result['status_item'] = TRUE;
        $result['message_item']['name'] = array();
        $result['message_item']['home_menu'] = "";
        $result['message_item']['menu_access'] = "";

        $language_master = Language::select(
            'lang_code AS id',
            'lang_name AS name'
        )->get();

        if(!empty($request->name)){
            $i = 0;
            foreach ($request->name as $key => $value) {
                if(empty($request->name[$key])){
                    $result['status_item'] = $result['status_item'] && FALSE;
                    $result['message_item']['name'][$i] = multi_lang('name')." ".$request->name_name[$key]." ".multi_lang('required');
                }else{
                    $result['message_item']['name'][$i] = "";
                }
                $i++;
            }
        }
        if(empty($request->home_menu)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['home_menu'] = multi_lang('home_menu')." ".multi_lang('required');
        }
        if(!$request->has('menu')){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['menu_access'] = multi_lang('menu_access')." ".multi_lang('required');
        }

        if($result["status_item"]){

            try {
                $menu = $request->menu;
                if(!in_array($request->home_menu, $menu)){
                    array_push($menu, $request->home_menu);
                }

                $mg = new MenuGroup;
                $mg->menugroup_home_menu_id = $request->home_menu;
                $mg->menugroup_menu_id = implode(",", $menu);
                $mg->created_at = date('Y-m-d H:i:s');
                $mg->created_by = auth()->user()->id;

                if($mg->save()){
                    if(!empty($request->name)){
                        $data_insert = [];
                        foreach ($request->name as $key => $value) {
                            $data_insert[] = array(
                                'menugroupname_menugroup_id' => $mg->menugroup_id,
                                'menugroupname_lang_code' => $key,
                                'menugroupname_name' => $value
                            );
                        }
                        MenuGroupName::insert($data_insert);
                    }
                }

                $result["status"] = TRUE;
                $result["message"] = multi_lang('success_add_data');

            } catch (\Throwable $th) {
                $result["status"] = FALSE;
                $result["message"] = multi_lang('failed_add_data');
            }

        }else{
            $result["status"] = FALSE;
        }

        return response()->json($result, 200);
    }

    public function edit($id)
    {
        $data['detail'] = User::where('id', $id)->first();

        $data['role_master'] = MenuGroup::select(
            'menu_group.menugroup_id AS id',
            'mgn.menugroupname_name AS name',
        )
        ->leftJoin('menu_group_name AS mgn', function($join){
            $join->on('mgn.menugroupname_menugroup_id', 'menu_group.menugroup_id');
            $join->on('mgn.menugroupname_lang_code', DB::raw("'".auth()->user()->lang_code."'"));
        })
        ->orderBy('mgn.menugroupname_name', 'asc')
        ->get()
        ;

        $data['language_master'] = Language::select(
            'lang_code AS id',
            'lang_name AS name'
        )->get();

        $data['status_master'] = array(
            array('id' => 1, 'name' => multi_lang('active')),
            array('id' => 0, 'name' => multi_lang('not_active')),
        );

        $data['status_master'] = json_decode(json_encode($data['status_master']), FALSE);

        return view('access-right.edit', $data);
    }

    public function doEdit(Request $request)
    {
        $result["status"] = TRUE;
        $validation_text = "";
        $result['status_item'] = TRUE;
        $result['message_item']['name'] = "";
        $result['message_item']['email'] = "";
        $result['message_item']['role'] = "";
        $result['message_item']['language'] = "";
        $result['message_item']['status'] = "";
        $result['message_item']['password'] = "";
        $result['message_item']['re_password'] = "";

        if(empty($request->name)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['name'] = multi_lang('name')." ".multi_lang('required');
        }
        if(empty($request->email)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['email'] = multi_lang('email')." ".multi_lang('required');
        }else{
            $check_user = User::select('id')->where('email', $request->email)->whereNotIn('id', [$request->id])->first();
            if(!empty($check_user->id)){
                $result['status_item'] = $result['status_item'] && FALSE;
                $result['message_item']['email'] = multi_lang('email')." ".multi_lang('is_registered');
            }
        }
        if(empty($request->role)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['role'] = multi_lang('role')." ".multi_lang('required');
        }
        if(empty($request->language)){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['language'] = multi_lang('language')." ".multi_lang('required');
        }
        if($request->has('status') && $request->status == ""){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['status'] = multi_lang('status')." ".multi_lang('required');
        }
        if(!empty($request->password) && !empty($request->re_password) && $request->password != $request->re_password){
            $result['status_item'] = $result['status_item'] && FALSE;
            $result['message_item']['re_password'] = multi_lang('password_and_re_password_not_match');
        }

        if($result["status_item"]){
            $user = User::where('id', $request->id)->first();

            $user->name = $request->name;
            $user->email = $request->email;
            if(!empty($request->password) && !empty($request->re_password) && $request->password == $request->re_password){
                $user->password = Hash::make($request->password);
            }
            $user->status = $request->status;
            $user->role = $request->role;
            $user->lang_code = $request->language;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->updated_by = auth()->user()->id;

            if($user->save()){
                $result["status"] = TRUE;
                $result["message"] = multi_lang('success_edit_data');
            } else {
                $result["status"] = FALSE;
                $result["message"] = multi_lang('failed_edit_data');
            }
        }else{
            $result["status"] = FALSE;
        }

        return response()->json($result, 200);
    }

    public function detail($id)
    {
        $data['detail'] = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.status',
            'users.last_login',
            'users.role',
            'mgn.menugroupname_name AS role_name',
            'users.lang_code',
            'cl.lang_name AS lang_code_name',
            DB::raw('IFNULL(DATE_FORMAT(users.created_at,"%d/%m/%Y %H:%i:%s"),"") AS created_date'),
            'users.created_by',
            'users_a.name AS created_by_name',
            DB::raw('IFNULL(DATE_FORMAT(users.updated_at,"%d/%m/%Y %H:%i:%s"),"") AS updated_date'),
            'users.updated_by',
            'users_b.name AS updated_by_name'
        )
        ->leftJoin('menu_group AS mg', 'mg.menugroup_id', 'users.role')
        ->leftJoin('menu_group_name AS mgn', 'mgn.menugroupname_menugroup_id', 'mg.menugroup_id')
        ->leftJoin('core_lang AS cl', 'cl.lang_code', 'users.lang_code')
        ->leftJoin('users AS users_a', 'users_a.id', 'users.created_by')
        ->leftJoin('users AS users_b', 'users_a.id', 'users.updated_by')
        ->where('mgn.menugroupname_lang_code', auth()->user()->lang_code)
        ->where('users.id', $id)->first();

        $data['detail']->status = $data['detail']->status == 1 ? multi_lang('active') : multi_lang('not_active');
        if(!empty($data['detail']->created_date) && !empty($data['detail']->created_by_name)){
            $data['detail']->created =  $data['detail']->created_date." ".multi_lang('by')." ".$data['detail']->created_by_name;
        }else{
            $data['detail']->created = "-";
        }
        if(!empty($data['detail']->updated_date) && !empty($data['detail']->updated_by_name)){
            $data['detail']->updated =  $data['detail']->updated_date." ".multi_lang('by')." ".$data['detail']->updated_by_name;
        }else{
            $data['detail']->updated = "-";
        }

        return view('access-right.detail', $data);
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->first();

        if($user->delete()){
            $result["status"] = TRUE;
            $result["message"] = multi_lang('success_delete_data');
        } else {
            $result["status"] = FALSE;
            $result["message"] = multi_lang('failed_delete_data');
        }

        return response()->json($result, 200);
    }
}
