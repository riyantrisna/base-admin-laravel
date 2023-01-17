<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        $data['title'] = " - Login";
        return view('layouts.login', $data);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'Email dibutuhkan.',
                'password.required' => 'Password dibutuhkan',
            ]
        );

        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1
        ];
        if (Auth::attempt($data)) {

            $menu_default = User::getMenuDefault();

            if(!empty($menu_default)){
                $request->session()->regenerate();

                $user = User::where('email', $request->email)->first();
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();

                return redirect()->intended($menu_default);
            }else{

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return back()->with([
                    'loginError'=> 'Anda tidak memiliki akses!',
                    'email'=> $request->email,
                ]);
            }
        }

        return back()->with([
            'loginError'=> 'Username atau Password salah!',
            'email'=> $request->email,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function changePassword(Request $request)
    {
        $result["status"] = TRUE;
        $validation_text = "";

        if(empty($request->old_password)){
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('old_password')." ".multi_lang('required')."</li>";
        }

        if(empty($request->new_password)){
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('new_password')." ".multi_lang('required')."</li>";
        }

        if(empty($request->re_new_password)){
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('re_new_password')." ".multi_lang('required')."</li>";
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
                $result["status"] = $result["status"] && FALSE;
                $validation_text.= "<li>".multi_lang('old_password_is_wrong')."</li>";
            }
        }else{
            $result["status"] = $result["status"] && FALSE;
            $validation_text.= "<li>".multi_lang('user_not_found')."</li>";
        }

        if($result["status"]){

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
        }else{
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
