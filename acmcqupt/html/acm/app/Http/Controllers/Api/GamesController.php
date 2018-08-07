<?php

namespace App\Http\Controllers\Api;

use App\Models\Baoming;
use App\Models\Game;
use App\Models\Gameidnumber;
use App\Models\Peoplebaoming;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Storage;
use Mail;
class GamesController extends Controller
{
    public function create(Request $request){

            $game = Game::create([
                'gamename' => $request->gamename,
                'fuzeren' => $request->fuzeren,
                'fuzerenphone' => $request->fuzerenphone,
                'gametime' => $request->gametime,
                'address' => $request->address,
                'origanizetion' => $request->origanizetion,
                'guize' => $request->guize,
                'number' => 0,
                'news' => $request->news,
                'gamestatus' => 0,
            ]);
            $baoming = Baoming::where('id',1)->first();
            $game->baomingid = $baoming->number;
            $game->save();
            $baoming->number = $baoming->number+1;
            $baoming->save();
            Gameidnumber::create([
                'gameid' =>$game->id,
                'number' =>0
            ]);
            return $this->response->array([
                'info' => '发布成功',
                'status_code' => 200,
            ])->setStatusCode(201);

    }
    public function show(Request $request){
        $games = Game::all();
        $gamesarr = array();
        $i=0;
        $gamesarr=$games->all();
        return $this->response->array([
            'info' => $gamesarr,
            'status_code' => 200,
        ])->setStatusCode(200);
    }

    public function showone(Request $request){
        $this->validate($request,[
           'gameid' => 'required',
        ]);
        $gameid = $request->gameid;
        $filename = $gameid.".xls";
        $content =  Storage::disk('uploads')->has($filename);
        $i = 0;
        $pass2 = array();
        if($content!=null){
            $dbpass = 'game_'.$gameid.'_pass';
            $sql = 'select * from '.$dbpass;
            $pass = DB::select($sql);
            foreach ($pass as $passone){
                $pass2[$i]['id'] = $passone->id;
                $pass2[$i]['team_name'] = $passone->team_name;
                $pass2[$i]['login_id'] = $passone->login_id;
                $pass2[$i]['password'] = $passone->password;
                $pass2[$i]['schoolid'] = $passone->schoolid;
                $pass2[$i]['email'] = $passone->email;
                $i++;
            }
        }
        $people = Peoplebaoming::where('gameid',$gameid)->get();
//        $peoplearr = $people->toArray();
        $j = 0;
        foreach ($people as $p){
            if($j<$i){
                $j++;
                continue;
            }else{
                $pass2[$j]['id'] = $p->baomingid;
                $pass2[$j]['team_name'] = "未指定";
                $pass2[$j]['login_id'] = "未指定";
                $pass2[$j]['password'] ="未指定";
                $pass2[$j]['schoolid'] = $p->schoolid;
                $pass2[$j]['email'] = $p->email;
                $j++;
            }
        }
        return $this->response->array([
            'info' => $pass2,
            'status_code' => 200,
        ])->setStatusCode(200);
    }
    public function delete(Request $request){
        $this->validate($request,[
            'key' => 'required',
        ]);
        $key = $request->key;
        if($request->session()->has($key)){

        }else{
            return $this->response->array([
                'info' => '请登录后重试',
                'status_code' => 400,
            ])->setStatusCode(200);
        }
    }
    public function update(Request $request){
        $this->validate($request,[
            'key' => 'required',
        ]);
        $key = $request->key;
        if($request->session()->has($key)){

        }else{
            return $this->response->array([
                'info' => '请登录后重试',
                'status_code' => 400,
            ])->setStatusCode(200);
        }
    }
    //读取excel
    public function import($filePath){
//        $filePath = 'storage/exports/'.iconv('UTF-8', 'GBK', '学生成绩').'.xls';
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }
    public function downloadbmb(Request $request){
        $this->validate($request,[
            'gameid' => 'required',
        ]);
        $gameid = $request->gameid;
        $peoplebaomings=Peoplebaoming::where('gameid',$gameid)->get();
        $arraypeoplebaomings = $peoplebaomings->toArray();
        $filename = 'baomingmsg'.$gameid;
        Excel::create($filename,function($excel) use ($arraypeoplebaomings){
            $excel->sheet('message', function($sheet) use ($arraypeoplebaomings){
                $sheet->rows($arraypeoplebaomings);
            });
        })->store('xls',public_path('app/downloadbmb'));
        $file = public_path('app/downloadbmb/'.$filename.'.xls');
        $header = array([
            'Content-type' => 'application/vnd.ms-office',
        ]);
        return response()->download($file,$filename.'.xls',$header);

    }
    public function create_table($table_name,$arr_field,$chu_zai,$gameid)
    {

        $tmp = $table_name;
        $va = $arr_field;
        if($chu_zai==1) Schema::drop($tmp);
        Schema::create("$tmp", function(Blueprint $table) use ($tmp,$va)
        {
            $fields = $va[0];  //列字段
            //$fileds_count =  0; //列数
            $table->increments('id');//主键
            foreach($fields as $key => $value){
                if($key == 0){
                    $table->string($fields[$key])->nullable();//->unique(); 唯一
                }else{
                    $table->string($fields[$key])->nullable();
                }
                //$fileds_count = $fileds_count + 1;
            }
            $table->bigInteger('schoolid')->nullable();
            $table->string('email')->nullable();
        });

        $value_str= array();
        $id = 1;
        //获取报名表的所有学号和邮箱
        $baomingusers = Peoplebaoming::where('gameid',$gameid)->get(['schoolid','email']);
        $cnt=0;
        foreach($va as $key => $value){
            if($key != 0){

                $content = implode(",",$value);
                $content2 = explode(",",$content);
                foreach ( $content2 as $key => $val ) {
                    $value_str[] = "'$val'";
                }
                $news = implode(",",$value_str);
                $news = "$id,".$news;
                //从报名数据库拿出每个数据放进到这个数据库中
                $i=0;
                $flag=0;
                foreach ($baomingusers as $baominguser){
                    if($i==$cnt){
                        $flag=1;
                        $news = $news.','."'$baominguser->schoolid'".','."'$baominguser->email'";
                        break;
                    }
                    $i++;
                }
                if($flag==0)continue;
                DB::insert("insert into $tmp values ($news)");
                //$value_str = '';
                $value_str= array();
                $id = $id + 1;
                $cnt++;
            }
        }
        return 1;
    }

    public function uploadpass(Request $request){
        $this->validate($request,[
            'gameid' => 'required',
        ]);
//        $key = $request->key;
//        if($request->session()->has($key)){
            $file = $request->file('userpass');
            if ($file->isValid()){
                $realPath = $file->getRealPath();
                $gameid = $request->gameid;
                $filename = $gameid.'.xls';
                $content =  Storage::disk('uploads')->has($filename);
                if($content!=null){
                    $cunzai = 1;
                }else{
                    $cunzai = 0;
                }
                Storage::disk('uploads')->put($filename, file_get_contents($realPath));
                $filename1 = 'storage\app\uploads\\'.$filename;
                $tablename = 'game_'.$gameid.'_pass';
                Excel::load($filename1, function($reader) use ($tablename,$cunzai,$gameid){
                    $reader = $reader->getSheet(0);
                    //获取表中的数据
                    $data = $reader->toArray();
                    $result = $this->create_table($tablename,$data,$cunzai,$gameid);
                });
                return view('upload');
            }else{
                return view('uploadfalse');
            }
//        }else{
//            return $this->response->array([
//                'info' => '请登录后重试',
//                'status_code' => 400,
//            ])->setStatusCode(200);
//        }
    }

    public function sendpass(Request $request){
        $this->validate($request,[
           'gameid' => 'required',
        ]);
            //生成分配的账号密码数据表
            $gameid = $request->gameid;
            $dbpass = 'game_'.$gameid.'_pass';
            $sql = 'select * from '.$dbpass;
            $pass = DB::select($sql);
            $i = 0 ;
            $game = Game::where('id',$gameid)->first();
            foreach ($pass as $onepass){
                //发送邮件
                $email = $onepass->email;
                Mail::send('sendpass',['schoolid' => $onepass->schoolid,'username' => $onepass->login_id,'password' => $onepass->password,'gamename' => $game->gamename,'gametime' => $game->gametime,'address'=>$game->address],function($message) use ($email){
                    $to = $email;
                    $message ->to($to)->subject('比赛账号密码');
                });
            }
            return $this->response->array([
                'info' => '发送成功',
                'status_code' => 200,
            ])->setStatusCode(200);
    }

    public function sendpassone(Request $request){
        $this->validate($request,[
           'key' => 'required',
           'gameid' => 'required',
           'schoolid' => 'required',
        ]);
        //如果这个用户有账号密码，就直接发，如果这个用户没有账号密码，就接收前端传来的账号密码
        $key = $request->key;
        if($request->session()->has($key)){
            $gameid = $request->gameid;
            $dbpass = 'game_'.$gameid.'_pass';
            $schoolid = $request->schoolid;
            $where = 'where `schoolid`='.$schoolid;
            $sql = 'select * from '.$dbpass.' '.$where;
            $pass = DB::select($sql);
            if($pass==null){
                $login_id =$request->loginid;
                $password = $request->password;
                $email = $request->email;
                $sql2 = 'select count(*) as total from '.$dbpass;
                $numberpass = DB::select($sql2);
                $number = $numberpass[0]->total+1;
                $news = "$number,'your_own_nick','$login_id','$password',$schoolid,'$email'";
                DB::insert("insert into $dbpass values ($news)");
                $game = Game::where('id',$gameid)->first();
                Mail::send('sendpass',['schoolid' => $schoolid,'username' => $login_id,'password' => $password,'gamename' => $game->gamename,'gametime' => $game->gametime,'address'=>$game->address],function($message) use ($email){
                    $to = $email;
                    $message ->to($to)->subject('比赛账号密码');
                });
            }else{
                $email = $pass[0]->email;
                $game = Game::where('id',$gameid)->first();
                Mail::send('sendpass',['schoolid' => $schoolid,'username' => $pass[0]->login_id,'password' => $pass[0]->password,'gamename' => $game->gamename,'gametime' => $game->gametime,'address'=>$game->address],function($message) use ($email){
                    $to = $email;
                    $message ->to($to)->subject('比赛账号密码');
                });
            }
            return $this->response->array([
                'info' => '发送成功',
                'status_code' => 200,
            ])->setStatusCode(200);
        }else{
            return $this->response->array([
                'info' => '请登录后重试',
                'status_code' => 400,
            ])->setStatusCode(200);
        }
    }

    public function downloaduserpass(Request $request){
        $this->validate($request,[
            'gameid' => 'required',
        ]);
            $gameid = $request->gameid;
            $dbpass = 'game_'.$gameid.'_pass';
            $sql = 'select * from '.$dbpass;
            $pass = DB::select($sql);
            $i = 0;
            $pass2 = array();
            foreach ($pass as $passone){
                $pass2[$i]['id'] = $passone->id;
                $pass2[$i]['team_name'] = $passone->team_name;
                $pass2[$i]['login_id'] = $passone->login_id;
                $pass2[$i]['password'] = $passone->password;
                $pass2[$i]['schoolid'] = $passone->schoolid;
                $pass2[$i]['email'] = $passone->email;
                $i++;
            }
            $filename = 'baomingpassmsg'.$gameid;
            Excel::create($filename,function($excel) use ($pass2){
                $excel->sheet('详细信息', function($sheet) use ($pass2){
                    $sheet->rows($pass2);
                });
            })->store('xls',public_path('app/downloadpass'));
            $file = public_path('app/downloadpass/'.$filename.'.xls');
            $header = array([
                'Content-type' => 'application/vnd.ms-office',
            ]);
            return response()->download($file,$filename.'.xls',$header);

    }
    public function showallgames(Request $request){
        $games = Game::all();
        $gamesarr = array();
        $i=0;
        $gamesarr=$games->all();
        return $this->response->array([
            'info' => $gamesarr,
            'status_code' => 200,
        ])->setStatusCode(200);
    }

}
