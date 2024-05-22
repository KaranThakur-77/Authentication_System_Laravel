<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required',
            'password'=>'required|min:8'
        ]);


        if ($validator->passes()) {
            if (Auth::attempt(['email'=>$request->email,'password'=>md5($request->password)])) {
                return redirect()->route('account.dashboard');
            } else {
                return redirect()->route('account.login')->with('invalid','Either email or password is invalid');
            }
            

        }else{
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
