<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Gameidnumber;
use App\Models\Peoplebaoming;
use App\Models\User;
use App\Models\Userlogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;
class UsersController extends Controller
{
    //用户登录
    public function home(Request $request){
        $this->validate($request,[
           'schoolid' => 'required',
           'password' => 'required',
        ]);
//        $request->session()->flush();
        $schoolid = $request->schoolid;
        $schoolidkey = 'login'.$schoolid;
        $value = $request->session()->has($schoolidkey);
        if($value == false){
            $password = $request->password;
            $user = User::where('schoolid',$schoolid)->first();
            if($user!=null){
                if(Hash::check($password,$user->password)){
                    $userlogin = Userlogin::where('schoolid',$schoolid)->first();
                    $userlogin->ci = $userlogin->ci + 1;
                    $userlogin->save();
                    $key = str_random(15);
                    $keykey = 'login'.$schoolid;
                    $request->session()->put($keykey,$key);
                    $request->session()->put($key,$schoolid);
                    $rs['info'] = $schoolid.',这是您第'.$userlogin->ci.'登录,欢迎回来';
                    $rs['verkey'] = $key;
                    $rs['status_code'] = 200;
                    return response($rs,200);
                }else{
                    $rs['info'] = '密码错误';
                    $rs['status_code'] = 400;
                    return response($rs,200);
                }
            }else{
                $rs['info'] = '您还没有注册';
                $rs['status_code'] = 400;
                return response($rs,200);
            }
        }else{
            $rs['info'] = $schoolid.'同学你好，登录失败，请重试';
            $key = $request->session()->get($schoolid);
            $request->session()->forget($schoolidkey);
            $request->session()->forget($key);
            $rs['status_code'] = 400;
            return response($rs,201);
        }
    }

    //用邮件发送验证码，并存储到缓存中
    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|unique:users',
        ]);
        // 生成4位随机数，左侧补0
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        $email = $request->email;
        //发送邮件验证码
        $flag = Mail::send('test',['code'=>$code],function($message) use ($email){
            $to = $email;
            $message ->to($to)->subject('验证码');
        });
        $info = "邮件已发送，如长时间没收到邮件，请重试";

        $key = 'RegisterCodes'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['email' => $request->email, 'code' => $code], $expiredAt);

        return $this->response->array([
            'verkey' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'info' => $info,
            'status_code' => 200,
        ])->setStatusCode(200);
    }
    //找回密码时邮件发验证码
    public function findpasstore(Request $request){
        $this->validate($request,[
            'schoolid' => 'required|max:10',
        ]);
        $schoolid = $request->schoolid;
        $schoolidkey = 'login'.$schoolid;
        $value = $request->session()->has($schoolidkey);
        if ($value == false){
            //发邮件
            $user = User::where('schoolid',$schoolid)->first();
            $email = $user->email;
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            $name = $user->name;
            Mail::send('findpass',['name'=>$name,'code'=>$code],function($message) use ($email){
                $to = $email;
                $message ->to($to)->subject('找回密码验证码');
            });
            $info = "邮件已发送，如长时间没收到邮件，请重试";
            $key = 'findpassCode'.str_random(15);
            $expiredAt = now()->addMinutes(10);
            // 缓存验证码 10分钟过期。
            \Cache::put($key, ['email' => $request->email, 'code' => $code], $expiredAt);
            return $this->response->array([
                'key' => $key,
                'expired_at' => $expiredAt->toDateTimeString(),
                'info' => $info,
            ])->setStatusCode(201);
        }else{
            $rs['status_code'] = 400;
            $rs['info'] = '您已经登录，不能重置密码';
            return response($rs,200);
        }
    }

    //找回密码逻辑
    public function findpass(Request $request){
        $this->validate($request,[
           'schoolid' => 'required|max:10',
           'password' => 'required|min:8',
            'verification_code' => 'required|max:4',
        ]);
        $schoolid = $request->schoolid;
        $schoolidkey = 'login'.$schoolid;
        $value = $request->session()->has($schoolidkey);
        if ($value == false){
            $verifyData = \Cache::get($request->verification_key);
            if (!$verifyData) {
                return response('验证码失效',200);
            }
            $code = $request->verification_code;
            if (hash_equals($verifyData['code'],$code)){
                $user = User::where('schoolid',$schoolid)->first();
                $user->password = bcrypt($request->password);
                $user->save();
                return $this->response->array([
                    'status_code' => 200,
                    'info' => '重置成功',
                ])->setStatusCode(200);
            }else{
                return $this->response->array([
                    'status_code' => 400,
                    'info' => '验证码错误',
                ])->setStatusCode(200);
            }
        }else{
            $rs['status_code'] = 400;
            $rs['info'] = '您已经登录，不能重置密码';
            return $this->response->accepted($rs);
        }
    }

    public function baoming(Request $request){
        $this->validate($request,[
           'schoolid'=>'required|max:10',
           'phone'=>'required|max:11',
           'email'=>'required',
           'gameid'=>'required',
        ]);
        $game = Game::where('id',$request->gameid)->first();
        if($game->gamestatus==0){
            $gameidnumber = Gameidnumber::where('gameid',$request->gameid)->first();//
            Peoplebaoming::create([
                'baomingid' => $gameidnumber->number,
                'schoolid' => $request->schoolid,
                'phone' => $request->phone,
                'email' => $request->email,
                'xueyuan' => $request->xueyuan,
                'zhuanye' => $request->zhuanye,
                'gameid' => $request->gameid,
            ]);
            $gameidnumber->number = $gameidnumber->number+1;
            $gameidnumber->save();
            $game->number = $gameidnumber->number;
            $game->save();
            return $this->response->array([
                'info' => '报名成功',
                'status_code' => 200,
            ])->setStatusCode(200);
        }else{
            $this->response->array([
                'info' => '该竞赛暂时无法报名',
                'status_code'=>400,
            ])->setStatusCode(200);
        }
    }
}
