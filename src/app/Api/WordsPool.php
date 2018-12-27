<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Cookie;
use PhalApi\Exception;
use App\Domain\WordsPool as DomainWordsPool;

/**
 * Words Pool
 */
class WordsPool extends Api {
    public function getRules() {
        return array(
            'insert' => array(
                'openid' => array('name' => 'openid', 'require' => true, 'desc' => 'openid'),
                'word_id' => array('name' => 'word_id', 'desc' => 'Words id'),
                'status' => array('name' => 'status', 'type' => 'int','desc' => '已/未读'),
                'forget' => array('name' => 'forget','type' => 'int', 'desc' => '忘记次数'),
                'pass' => array('name' => 'pass','type' => 'int', 'desc' => 'pass掉'),
                'review' => array('name' => 'review','type' => 'int', 'desc' => '复习次数'),
                'review_date' => array('name' => 'review_date','desc' => 'pass掉'),
                'dict' => array('name' => 'dict', 'type' => 'int', 'default' => 0, 'desc' => 'word book'),
            ),
            'selectWordBook' => array(
                'openid' => array('name' => 'openid', 'require' => true, 'desc' => 'openid'),
                'book' => array('name' => 'book', 'type' => 'int', 'min' => 1, 'max' => 20, 'desc' => 'book id,currently only 1 book '),
                'reset' => array('name' => 'reset', 'type' => 'int', 'desc' => 'reset words pool'),
            ),
            'checkin' => array(
                'open_name'=>array('name' => 'open_name', 'source' => 'cookie','require' => true,'desc'=>'openid in cookie'),
                'method'=>array('name' => 'REQUEST_METHOD', 'source' => 'server'),
            ),
            'getReviewWords' => array(
//                'openid'=>array('name' => 'openid',  'require' => true, 'desc' => 'openid'),
                'openid'=>array('name' => 'openid', 'source' => 'cookie','require' => true,'desc'=>'openid in cookie'),
                'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => 'page'),
                'view' => array('name' => 'view', 'default' => 'view', 'desc' => 'return view / words'),
                'method'=>array('name' => 'REQUEST_METHOD', 'source' => 'server'),
            ),
            'getNewWords' => array(
                'openid'=>array('name' => 'openid',  'require' => true, 'desc' => 'openid'),
            ),
            'review' => array(
                'open_name'=>array('name' => 'open_name', 'source' => 'cookie','require' => true,'desc'=>'openid in cookie'),
                'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'method'=>array('name' => 'REQUEST_METHOD', 'source' => 'server'),
            ),
        );
    }

    /**
     * Choose vocabulary book.
     * @desc Choose a vocabulary book to learn , or reset words pool data learn all over words again
     * Entrance : wechat-'start'
     * @return false boolean or words amount
     */
    public function selectWordBook(){
        $domain = new DomainWordsPool();
        $rows = $domain->selectWordBook($this->openid,$this->book,$this->reset);
        return $rows ;
    }

    /**
     * Get new words
     *  @desc Return new words view
     */
    public function getNewWords(){
        \PhalApi\DI()->cookie=new Cookie();
         \PhalApi\DI()->cookie->set('open_name', $this->openid, $_SERVER['REQUEST_TIME'] + 60*30);
        $word = new DomainWordsPool();
        $word->getWordsByDate($this->openid);
    }

    /**
     * Check in
     * @desc Check in after learning new words, record the 'pass' and 'mark' words.
     * @throws Exception\BadRequestException
     */
    public function checkin(){
        if($this->method == 'POST'){
            $data=array();
            foreach($_POST as $k=> $v){
                if(substr($k,0,2)=='id'){
                    $data["id"][]=$v;
                }else{
                    $id=substr($k,5);
                    $field=substr($k,0,4);
                    $data["$field"][]= $id;
                }
            }
            $wordsPool= new DomainWordsPool();
            $wordsPool->checkin($this->open_name,$data);
        }else{
            $this->invalidRequest();
        }
    }
    /**
     * Get review words
     * @desc Review page 'Finish'/'Next Page' submit review data and get 10 more review words.
     * @return words string / view
     */
    public function getReviewWords(){
        if($this->method == 'POST'){
            $data=array();
            foreach($_POST as $k=> $v){
                switch ($k){
                    case substr($k,0,2)=='id':
                        $data["id"][]=$v;
                        break;
                    case substr($k,0,6)=='forget':
                        $data["forget"][]=substr($k,7);
                        break;
                    case substr($k,0,4)=='mark':
                        $data["mark"][]=substr($k,5);
                        break;
                    case substr($k,0,4)=='pass':
                        $data["mark"][]=substr($k,5);
                        break;
                    default:
                        continue;
                        break;
                }
            }
            $wordsPool= new DomainWordsPool();
            $wordsPool->review($this->openid, $data);
        }
//        \PhalApi\DI()->cookie=new Cookie();
//        \PhalApi\DI()->cookie->set('open_name', $this->openid, $_SERVER['REQUEST_TIME'] + 60*30);
        $wordsPool= new DomainWordsPool();
        $words= $wordsPool->getReviewWords($this->openid,$this->page,$this->view);
        if($this->view=='chat'){
            echo $words;exit();
        }
    }
} 
