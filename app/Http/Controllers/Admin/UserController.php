<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use File;
use Session;
use Validator;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.pages.profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $requestData = $request->all();
        $errors = Validator::make($requestData, [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
                    'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            $user = User::find(Auth::user()->id);

            if ($user) {
                $image_name = '';
                if ($request->hasFile('image')) {
                    $image_path = public_path('sitebucket/userProfile/').Auth::user()->image;  // Value is not URL but directory file path
                    if (file_exists($image_path)) {
                        File::delete($image_path);
                    }
                    $image = $request->file('image');
                    $image_name = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/sitebucket/userProfile');
                    $image->move($destinationPath, $image_name);
                    $user->image = isset($image_name) ? $image_name : '';
                }

                $user->name = isset($requestData['name']) ? $requestData['name'] : '';
                $user->phone_number = isset($requestData['phone_number']) ? $requestData['phone_number'] : '';
                $user->email = isset($requestData['email']) ? $requestData['email'] : '';
                $user->address = isset($requestData['address']) ? $requestData['address'] : '';
                $user->save();
                Session::flash('success', 'Profile update successfully.');
                return redirect()->route('profile.index');
            } else {
                Session::flash('error', 'User not found!');
                return redirect()->route('profile.index');
            }
        }
    }

    public function changePassword() {
        return view('admin.pages.profile.changepassword');
    }

    public function changePasswordSave(Request $request) {
        $requestData = $request->all();
        $errors = Validator::make($requestData, [
                    'password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
        ]);
        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors->errors())->withInput($request->all());
        } else {
            if (!Hash::check($requestData['password'], Auth::user()->password)) {
                Session::flash('error', 'Your current password does not matches with the password you provided. Please try again.');
            } else {
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($requestData['new_password']);
                $user->save();
                Session::flash('success', 'Password change successfully.');
            }
            return redirect()->route('profile.changepassword');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
