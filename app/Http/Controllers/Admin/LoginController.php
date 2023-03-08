<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Admin;

class LoginController extends Controller {

    use AuthenticatesUsers;

    protected $redirectTo = 'admin/dashboard';

    public function __construct() {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm() {
        return view('adminauth.login');
    }

    protected function guard() {
        return Auth::guard('admin');
    }

    protected function credentials(Request $request) {
        return array_merge($request->only($this->username(), 'password'), ['status' => 'Active']);
    }

    protected function sendFailedLoginResponse(Request $request) {
        $errors = [$this->username() => trans('auth.failed')];

        // Load user from database
        $user = Admin::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->status != 'Active') {
            $errors = [$this->username() => trans('auth.notactivated')];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
                        ->withInput($request->only($this->username(), 'remember'))
                        ->withErrors($errors);
    }

    protected function logout() {
        auth('admin')->logout();
        return redirect('admin');
    }

}
