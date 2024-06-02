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
            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::guard('web')->user()->role != 'user') {
                    Auth::guard('web')->logout();
                    return redirect()->route('account.login')->with('error', 'You are not an user.');
                }
                
                return redirect()->route('account.dashboard'); // Ensure this route exists
            } else {
                return redirect()->route('account.login')->with('invalid', 'Either email or password is invalid');
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
