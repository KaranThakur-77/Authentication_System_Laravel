<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class adminController extends Controller
{
    public function index(){
        return view('admin.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required|email',
            'password'=>'required|min:8'
        ]);

        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::guard('admin')->user()->role != 'admin') {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You are not an admin.');
                }
                
                return redirect()->route('admin.dashboard'); // Ensure this route exists
            } else {
                return redirect()->route('admin.login')->with('invalid', 'Either email or password is invalid');
            }
        } else {
            return redirect()->route('admin.login')->withInput()->withErrors($validator);
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
