<?php

namespace App\Http\Controllers\admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{

    public function __construct(){
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm(){

        return view('admin.auth.login');
    }

    public function email(){
        return 'email';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request){


        $this->validate($request,[
            'email'    => 'required | email',
            'password' => 'required | min:6'
        ]);

        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if(Auth::guard('admin')->attempt($credentials,false)){

            return Redirect::to('admin/dashboard');
        }

        return Redirect::to('/admin');
    }

    public function logout(){

        Auth::guard('admin')->logout();
       return redirect(route('login'));
    }


   /* use AuthenticatesUsers;

    protected $redirectTo = '/home';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/
}
