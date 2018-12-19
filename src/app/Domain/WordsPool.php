<?php
namespace App\Domain;

use App\Common\Common;
use App\Model\WordsPool as ModelWordsPool;
use App\Model\Schedule as ModelSchedule;
use PhalApi\Exception;

class WordsPool {

    public function selectWordBook($openid,$book,$reset=0) {
        try{
            $schedule=new ModelSchedule();
            if($schedule->checkSchedule($openid,$book)||$reset){
                //TODO check transaction
                \PhalApi\DI()->notorm->beginTransaction('db_master');
                //update schedule
                $schedule->save($openid,$book);
                //insert words to words pool
                $wordsPool= new ModelWordsPool();
                $updateRow=$wordsPool->updateWordsPool($openid,$book);
                if($updateRow){
                    \PhalApi\DI()->notorm->commit('db_master');
                    return $updateRow;
                }else{
                    \PhalApi\DI()->notorm->rollback('db_master');
                    return false;
                }
            }
            return false;
        }catch (Exception $e){
            \PhalApi\DI()->notorm->rollback('db_master');
            return false;
        }
    }

    public function getWordsByDate($openid,$date=''){
        $date = $date ? $date:date('y-m-d');
        $view = new Common();
        $wordsPool = new ModelWordsPool();
        $words = $wordsPool->getNewWords($openid,$date);
        if($words){
            $view->render('view/words.php', array('words' => $words, 'days' => 1));
        }else{
            $view->render('view/response.php', array('msg' => 'Oops..No new words today , please check your schedule.'));
        }
    }

    public function checkin($openid,$data){
        $wordsPool= new ModelWordsPool();
        $view = new Common();

        if($wordsPool->checkin($openid,$data)){
            $view->render('view/response.php', array('msg' => 'Finished!'));
        }else{
            $view->render('view/response.php', array('msg' => 'Oops..Something wrong! Try again later.'));
        }
    }

    public function getReviewWords($openid,$page,$view=null){
        $wordsPool= new ModelWordsPool();
        $words=$wordsPool->getReviewWords($openid,$page,$view);
        if($view=='chat'){
            $chat='';
            foreach($words as $v){
                $chat.=$v['content']."\n ";
            }
            return $chat;
        }else{
            $view = new Common();
            if($words){
                $view->render('view/review.php', array('words' => $words, 'days' => 1,'page'=>$page+1));
            }else{
                $words=$wordsPool->totalReview($openid);
                $view->render('view/response.php', array('msg' =>'You have reviewed '.$words.' words today!'));
            }
        }
    }

    public function review($openid, $data){
        $wordsPool = new ModelWordsPool();
        $wordsPool->review($openid,$data);
    }
}
