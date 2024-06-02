<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Cache\RedisTagSet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index(){
        return view('register');
    }
    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'username'=>'required',
            'email' =>'required|unique:users',
            'password'=>'required|min:8|max:10|confirmed'
        ]);


        if ($validator->passes()) {
            $user = new User();
            $user->name=$request->username;
            $user->email=$request->email;
            $user->password= Hash::make($request->password);
            $user->role='user';
            $user->save();

            return redirect()->route('account.login')->with('success','Registration successful.');
    }
    else{
        return redirect()->route('account.register')->withInput()->withErrors($validator);
    }
}
}
