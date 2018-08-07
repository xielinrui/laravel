<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Userlogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersRegisterController extends Controller
{
    //
    public function store(Request $request){
        $this->validate($request,[
           'schoolid' => 'required|unique:users|max:10',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|max:16',
        ]);
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return response('验证码失效',200);
        }
        $code = $request->verification_code;
        if (hash_equals($verifyData['code'],$code)){
            //如果验证成功，就将所有信息存入数据库
            $user = User::create([
                'name' => $request->name,
                'schoolid' =>$request->schoolid,
                'email' => $verifyData['email'],
                'password' => bcrypt($request->password),
                'sex' => 0,
                'xueyuan'=>$request->xueyuan,
                'zhuanye'=>$request->zhuanye,
                'shifouhuiyuan' => 0,
            ]);
            $userlogin = Userlogin::create([
               'schoolid' => $request->schoolid,
                'ci' => 0,
                'status' => 0,
            ]);
            \Cache::forget($request->verification_key);
            $rs['info'] = "注册成功";
            $rs['status_code'] = 200;
            return response($rs,200);
        }else{
            $rs['info'] = '验证码错误';
            $rs['status_code'] = 400;
            return response($rs,200);
        }
    }
}
