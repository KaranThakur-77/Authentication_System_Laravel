<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(){
        return view('teacher.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required|email',
            'password'=>'required|min:8'
        ]);

        if ($validator->passes()) {
            if (Auth::guard('teacher')->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::guard('teacher')->user()->role != 'teacher') {
                    Auth::guard('teacher')->logout();
                    return redirect()->route('teacher.login')->with('error', 'Sorry!, You are not teacher.');
                }
                
                return redirect()->route('teacher.dashboard'); // Ensure this route exists
            } else {
                return redirect()->route('teacher.login')->with('invalid', 'Either email or password is invalid');
            }
        } else {
            return redirect()->route('teacher.login')->withInput()->withErrors($validator);
        }
    }

    public function logout(){
        Auth::guard('teacher')->logout();
        return redirect()->route('teacher.login');
    }
}
