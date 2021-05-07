<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }


    /**
     * Show the Login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }


    /**
     * Login Administrator
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate(
            $request,
            ['email'   => 'required|email',
             'password' => 'required|min:6']
        );

        // Attempt to log the admin in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            if (Auth::user()->superuser === 1) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }


    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request [the current request instance]
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
