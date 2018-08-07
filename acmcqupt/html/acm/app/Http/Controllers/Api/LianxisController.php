<?php

namespace App\Http\Controllers\Api;

use App\Models\Lianxi;
use Illuminate\Http\Request;

class LianxisController extends Controller
{
    //



    public function create(Request $request){
        $lianxi = Lianxi::create([
            'type' => $request->type,
            'name' => $request->name,
            'url' => $request->url,
            'usezhinan' =>$request->usezhinan,
        ]);
        return $this->response->array([
            'info' => '发布成功',
            'status_code' => 200,
        ])->setStatusCode(200);
    }

    public function showall(Request $request){
        $ans = Lianxi::all();
        $ansarr = $ans->all();
        return $this->response->array([
            'info' => $ansarr,
            'status_code' => 200,
        ])->setStatusCode(200);
    }

}
