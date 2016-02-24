<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/main/weixin/code_call_back' , 'WeixinAuthController@codeCallBack');
$app->get('/main/weixin/get_token_ticket' , 'WeixinAuthController@getTokenTicket');
$app->get('/main/weixin/get_sign' , 'WeixinAuthController@getSign');
