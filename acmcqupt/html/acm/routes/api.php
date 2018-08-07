<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => 600,
        'expires' => 600,
    ],function ($api){
        //短信验证码
        $api->post('registerCodes', 'UsersController@store')
            ->name('api.registerCodes.store');
        //用户注册
        $api->post('users','UsersRegisterController@store')
            ->name('api.users.store');
        //用户登录
        $api->post('login','UsersController@home')
            ->name('api.login.store');
        //找回密码验证码
        $api->post('findpassCodes','UsersController@findpasstore')
            ->name('api.findpassCodes.findpasstore');
        //找回密码
        $api->post('findpass','UsersController@findpass')
            ->name('api.findpass.findpass');
        //管理员登录
        $api->post('adminlogin','AdminsController@login')
            ->name('api.adminlogin.login');
        //管理员退出
        $api->post('adminlogout','AdminsController@logout')
            ->name('api.adminlogout.logout');
        //-------------------------
        //校外赛事相关
        //管理员对赛事的增删改查
        $api->post('showallworldgames','AcmworldgamesController@showall')
            ->name('api.showallworldgames.showall');
        $api->post('showoneworldgame','AcmworldgamesController@showone')
            ->name('api.showoneworldgame.showone');
        $api->post('createnewworldgame','AcmworldgamesController@create')
            ->name('api.createnewworldgame.create');
        $api->post('deleteworldgame','AcmworldgamesController@delete')
            ->name('api.deleteworldgame.delete');
        $api->post('changeworldgame','AcmworldgamesController@change')
            ->name('api.changeworldgame.change');
        //用户对赛事的查
        //--------------------------
        //练习地址相关
        //管理员对练习地址的crud
        $api->post('showallexercises','LianxisController@showall')
            ->name('api.showallexercises.showall');
        $api->post('showoneexercise','LianxisController@showone')
            ->name('api.showoneexercise.showone');
        $api->post('createnewexercise','LianxisController@create')
            ->name('api.createnewexercise.create');
        $api->post('deleteexercise','LianxisController@delete')
            ->name('api.deleteexercise.delete');
        $api->post('changeexercise','LianxisController@change')
            ->name('api.changeexercise.change');
        //---------------------------
        //重邮acm历史相关
        //历届会长
        //团队荣耀

        //--------------------------
        //ACM校赛相关
        //管理员管理比赛
        //管理员创建比赛
        $api->post('creategame','GamesController@create')
            ->name('api.creategame.create');
        //管理员删除比赛
        $api->post('deletegame','GamesController@delete')
            ->name('api.deletegame.delete');
        //管理员查看所有比赛
        $api->post('adminshowgames','GamesController@show')
            ->name('api.adminshowgames,show');
        //管理员查看某一个比赛
        $api->post('adminshowonegame','GamesController@showone')
            ->name("api.adminshowonrgame.showone");
        //管理员更新比赛
        $api->post('udpategame','GamesController@update')
            ->name('api.updategame.update');
        //管理员下载报名表
        $api->post('downloadbaoming','GamesController@downloadbmb')
            ->name('api.downloadbaoming.downloadbmb');
        //管理员上传账号密码的excel
        $api->post('uploadpass','GamesController@uploadpass')
            ->name('api.uploadpass.uploadpass');
        //管理员批量发送邮件
        $api->post('sendpass','GamesController@sendpass')
            ->name('api.sendpass.pass');
        //管理员单独给某人发邮件
        $api->post('sendpassone','GamesController@sendpassone')
            ->name('api.sendpassone.sendpassone');
        //账号密码对照表下载
        $api->post('downloaduserpass','GamesController@downloaduserpass')
            ->name('api.downloaduserpass.downloaduserpass');
        //用户报名比赛
        //单人报名
        $api->post('danrenbaominggame','UsersController@baoming')
            ->name('api.danrenbaominggame.baoming');
        //用户查看比赛
        $api->post('showallgames','GamesController@showallgames')
            ->name('api.showallgames.showallgames');
        //用户查看比赛规则

    });

});

