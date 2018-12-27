<?php
namespace App\Api;

use App\Common\Common;
use App\Domain\WordsPool;
use PhalApi\Api;
use PhalApi\Cookie;
use PhalApi\Exception\BadRequestException;

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Site extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username', 'default' => 'PhalApi', 'desc' => '用户名'),
            ),
        );
	}
	
	/**
	 * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
	 * @return string title 标题
	 * @return string content 内容
	 * @return string version 版本，格式：X.X.X
	 * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
	 */
	public function index() {
        return array(
            'title' => 'Hello ' . $this->username,
            'version' => PHALAPI_VERSION,
            'time' => $_SERVER['REQUEST_TIME'],
        );
	}
    public function test(){
        \PhalApi\DI()->cookie = new Cookie();
        \PhalApi\DI()->cookie->set('open_name', '11', $_SERVER['REQUEST_TIME'] + 6000);

        $openid= \PhalApi\DI()->cookie->get('open_name');
        if($openid) {
            $word = new WordsPool();
            $word->getWordsByDate($openid);
        }else{
            throw new BadRequestException('Login Expired',1);
        }
    }
    public function selectWordBook(){
        $wordsPool=new WordsPool();
        $start=$wordsPool->selectWordBook('11',1);
        if($start){
            return 'Total '.$start.' words added!';
        }else{
            return 'Sorry! Something Wrong Happened.';
        }
    }
    public function getReviewWords(){
        $words=new WordsPool();
        $words->getReviewWords('11',null,'chat');
    }
    public function getNewWords(){
        $words=new WordsPool();
        $words->getWordsByDate('11');
    }
}
