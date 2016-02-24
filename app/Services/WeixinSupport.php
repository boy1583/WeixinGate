<?php
/**
 * Created by PhpStorm.
 * User: youweixi
 * Date: 16/2/19
 * Time: 下午6:53
 */

namespace app\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class WeixinSupport
{
    protected $app_id = null;
    protected $app_secret = null;
    protected $weixin_id = null;
    protected $valid = false;

    /**
     * WeixinSupport constructor.
     * @param null $app_id
     * @param null $app_secret
     */
    public function __construct($param)
    {
        $this->weixin_id = $param['weixin_id'];
        $result = DB::table('weixins')
            ->where('id' , $this->weixin_id)
            ->get();
        if (count($result)){
            $this->valid = true;
            $this->app_secret = $result[0]->app_secret;
            $this->app_id = $result[0]->app_key;
        }
    }


    //string access_token
    //string ticket
    private function getAccessTokenAndTicket(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->app_id&secret=$this->app_secret";
        $client = new Client();
        $access_token = json_decode($client->request('GET', $url)->getBody()->getContents())->access_token;
        $url2 = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
        $ticket = json_decode($client->request('GET', $url2)->getBody()->getContents())->ticket;
        return ['token' => $access_token , 'ticket' => $ticket];
    }

    public function cacheAccessTokenAndTicket(){
        if (!$this->valid) return [];

        $result = $this->getAccessTokenAndTicket();
        //返回影响的行数
        $count = DB::table('weixin_token_tickets')
            ->where('weixin_id' , $this->weixin_id)
            ->update($result);
        //如果没有更新（首次）
        if (!$count){
            $result['weixin_id'] = $this->weixin_id;
            DB::table('weixin_token_tickets')->insert(
                ['weixin_id' => $this->weixin_id,
                'ticket' => $result['ticket'],
                'token' => $result['token']
                ]
            );
        }
        return $result;
    }

    public function getTicketAndToken(){
        if (!$this->valid) return [];

        $result = DB::table('weixin_token_tickets')
            ->where('weixin_id' , $this->weixin_id)
            ->select('token' , 'ticket')
            ->get();
        if (count($result)){
            return ['token' => $result[0]->token , 'ticket' => $result[0]->ticket];
        }else{
            return $this->cacheAccessTokenAndTicket();
        }
    }

    public function getSignPackage($url) {
        if (!$this->valid) return [];

        $jsapiTicket = $this->getAccessTokenAndTicket()['ticket'];
        // 注意 URL 一定要动态获取，不能 hardcode.
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $this->app_id,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

}