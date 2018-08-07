<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    //
    public function login(Request $request){
        $this->validate($request,[
           'username' => 'required',
            'password' => 'required',
        ]);
        $password = $request->password;
        $username = $request->username;
        if($request->session()->has($username)){
            return $this->response->array([
                'info' => '您已经登录',
                'status_code'=>400,
            ])->setStatusCode(200);
        }else{
            $admin = Admin::where('username',$username)->first();
            if(md5($password)==$admin->password){
                $adminkey = $username;
                $key = 'adminlogin'.str_random(15);
                $request->session()->put($adminkey,$key);
                $request->session()->put($key,$adminkey);
                return $this->response->array([
                    'key' => $key,
                    'info' => "http://acm.cqupt.edu.cn/adminshowallbaoming.html",
                    'status_code' => 200,
                ])->setStatusCode(200);
            }else{
                return $this->response->array([
                    'info' => '密码错误',
                    'status_code' => 400,
                ])->setStatusCode(200);
            }
        }

    }
    public function logout(Request $request){
        $this->validate($request,[
           'verification_key' => 'required',
        ]);
        $key = $request->verification_key;
        $admin = $request->session()->get($key);
        $request->session()->forget($key);
        $request->session()->forget($admin);
        return $this->response->array([
            'info' => '登出成功',
            'status_code' => 200,
        ])->setStatusCode(200);
    }
}
