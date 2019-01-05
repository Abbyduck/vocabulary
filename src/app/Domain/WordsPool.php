<?php
namespace App\Domain;

use App\Common\Common;
use App\Model\History;
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
        $view = new Common();
        $schedule = new ModelSchedule();
        $dict = $schedule->getDict($openid);
        $wordsPool = new ModelWordsPool();
        $words = $wordsPool->getNewWords($openid,$date,$dict);
        $history=new History();
        $day=$history->total($openid,$dict);
        if($words){
            $view->render('view/words.php', array('words' => $words, 'days' => $day+1));
        }else{
            $view->render('view/response.php', array('msg' => 'Oops..No new words today , please check your schedule.','location'=>1,'openid'=>$openid));
        }
    }

    public function checkin($openid,$data){
        $wordsPool= new ModelWordsPool();
        $view = new Common();

        if($wordsPool->checkin($openid,$data)){
            $view->render('view/response.php', array('msg' => 'Finished!','location'=>1,'openid'=>$openid));
        }else{
            $view->render('view/response.php', array('msg' => 'Oops..Something wrong! Try again later.'));
        }
    }

    public function getReviewWords($openid,$page,$view=null){
        $wordsPool= new ModelWordsPool();
        $words=$wordsPool->getReviewWords($openid,$page,$view);
        //TODO del
        if($view=='chat'){
            $chat='';
            foreach($words as $v){
                $chat.=$v['content']."\n";
            }
            return $chat;
        }else{
            $view = new Common();
            if($words){
                $schedule = new ModelSchedule();
                $dict = $schedule->getDict($openid);
                $history=new History();
                $day=$history->total($openid,$dict);
                $view->render('view/review.php', array('words' => $words, 'days' => $day, 'page'=>$page+1, 'openid'=>$openid));
            }else{
                $words=$wordsPool->totalReview($openid);
                $view->render('view/response.php', array('msg' =>'You have reviewed '.$words.' words today!'));
            }
        }
    }
    public function simpleReview($openid){
        $wordsPool= new ModelWordsPool();
        $words=$wordsPool->simpleReview($openid);
        $chat='';
        if($words){
            foreach($words as $v){
                $chat.=$v['content']."\n";
            }
        }else{
            $chat='No study today o~';
        }

        return $chat;
    }

    public function review($openid, $data){
        $wordsPool = new ModelWordsPool();
        $wordsPool->review($openid,$data);

    }
}
