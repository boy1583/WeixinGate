<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeixinAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
     * code转发
     * */
    public function codeCallBack(Request $request){
        $code = $request->input('code');
        $state = $request->input('state');
        $result = DB::table('weixin_code_routes')->where('state' , $state)->get();
        if (count($result)){
            return redirect($result[0]->route."?code=$code&state=$state");
        }else{
            return ["msg" => "target route is null"];
        }
    }

    /*
     * 获取token和ticket
     * */
    public function getTokenTicket(Request $request){
        $weixin_id = $request->input('weixin_id');
        if ($weixin_id == null) return ["msg" => "weixin_id is invalid"];
        $weixin_support = app('WeixinSupport' , ['weixin_id' => $weixin_id]);
        return $weixin_support->getTicketAndToken();
    }

    /*
     * 获取js-sign签名
     * */
    public function getSign(Request $request){
        $weixin_id = $request->input('weixin_id');
        $url = $request->input('url');
        if ($weixin_id == null) return ["msg" => "weixin_id is invalid"];
        $weixin_support = app('WeixinSupport' , ['weixin_id' => $weixin_id]);
        return $weixin_support->getTicketAndToken($url);
    }
}
