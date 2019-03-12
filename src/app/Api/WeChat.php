<?php
namespace App\Api;

use App\Domain\WordsPool;
use PhalApi\Api;
use EasyWeChat\Factory;
use App\Domain\User as DomainUser;
use PhalApi\Cookie;

/**
 * Wechat
*/

class WeChat extends Api {

    protected $app;

	public function __construct() {
        $this->app = Factory::officialAccount(\PhalApi\DI()->config->get('wx_officialAccount'));
	}
    /**
     * 微信接入地址
     */
    public function response_echostr(){
        // 验证echostr字符串
        if (isset($_GET['echostr']) && $_GET['echostr']){
            $this->app->server->serve()->send();
            die;
        }
        $this->app->server->push(function ($message) {
            $user_openid = $message['FromUserName'];
            $user_info['openid'] = $user_openid;
//            $userService = $this->app->user;
//            $user = $userService->get($user_info['openid']);
//            $user_info['nickname'] = $user['nickname'];
//            $user_info['sex'] = $user['sex'];
            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']) {
                        case 'subscribe':
                            if (WeChat::subscribe($user_info)) {
                                return 'Hi,Beauty~';
                            }else{
                                return 'Sorry,please follow again';
                            }
                            break;
                        case 'unsubscribe':
                            if (WeChat::unSubscribe($user_openid)) {
                                return 'Bye,Beauty~';
                            }
                            break;
                        case 'CLICK':
                            switch ($message['EventKey']) {
                                case 'V001'://simple review words
                                    $words=new WordsPool();
                                    return $words->simpleReview($user_openid);
                                    break;

                                case 'V002'://forget words
                                    return 'Coming soon!';
                                    break;

                                case 'V003'://temp new words
                                    return 'http://vocabulary.duckduck.online/?s=WordsPool.getNewWords&openid='.$user_openid;
                                    break;

                                case 'V004'://temp view words
                                    return 'http://vocabulary.duckduck.online/?s=WordsPool.getReviewWords&openid='.$user_openid;
                                    break;

                                default:
                                    return '其他点击事件!';
                                    break;
                            }
                            break;
                        default:
                            return '收到event事件消息';
                            break;
                    }
                    break;
                case 'text':
                    switch ($message['Content']) {
                        case 'start':
                            $wordsPool=new WordsPool();
                            $start=$wordsPool->selectWordBook($user_openid,1);
                            if($start){
                                return 'Total '.$start.' words added!';
                            }else{
                                return 'Sorry! Something Wrong Happened.';
                            }
                            break;
                        case 'restart':
                            $wordsPool=new WordsPool();
                            $start=$wordsPool->selectWordBook($user_openid,1,1);
                            if($start){
                                return 'Total '.$start.' words restart!';
                            }else{
                                return 'Sorry! Something Wrong Happened.';
                            }
                            break;
                        case strpos($message['Content'],"/::)",0):
                            $words = substr($message['Content'],4);
                            return $words;
                            //TODO add word
                            break;
                        default:
                            return \PhalApi\DI()->config->get('app.wechatReply');
                            break;
                    }
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                default:
                    return 'Hello~';
                    break;
            }
        });
        $this->app->server->serve()->send();
    }
    public function test()
    {
        $list = $this->app->menu->current();
        \PhalApi\DI()->logger->info('oauth callback',$list);
        return $list;
    }

    /**
     * 授权成功跳转地址,获得用户信息(需认证之后才能获得)
     * @return mixed
     */
    public function oauth_callback()
    {
        $user  = $this->app->oauth->user();
        \PhalApi\DI()->logger->info('oauth callback',$user);
    }

    public function subscribe($user){
        $User=new DomainUser();
        return $User->insert($user);
    }
    public function unSubscribe($openid){
        $User=new DomainUser();
        return $User->delete($openid);

    }


    /**
     * 添加菜单
     */
    public  function  menu_add(){
        $menus = [
                [
                    "name" => "More",
                    "sub_button"=>[
                        [
                            "type" => "click",
                            "name" => "Simple Review",
                            "key"  => "V001"
                        ],
                        [
                            "type" => "click",
                            "name" => "Forget Words",
                            "key"  => "V002"
                        ],
                    ]
                ],
                [
                    "type" => "click",
                    "name" => "New Words",
                    "key"  => "V003"
                ],
                [
                    "type" => "click",
                    "name" => "Review",
                    "key"  => "V004"
                ],

             ];
        return $this->app->menu->create($menus);
    }
}
