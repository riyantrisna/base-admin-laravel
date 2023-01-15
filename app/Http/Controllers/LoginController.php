<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
}
