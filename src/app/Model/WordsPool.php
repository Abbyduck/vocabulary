<?php
namespace App\Model;

use PhalApi\Exception;
use PhalApi\Model\NotORMModel as NotORM;
use App\Model\Words;

/**
 * CREATE TABLE `words_pool` (
 * `id` int(11) NOT NULL AUTO_INCREMENT,
 * `openid` varchar(45) NOT NULL,
 * `word_id` int(11) DEFAULT NULL,
 * `status` int(1) DEFAULT NULL COMMENT '1:read,0:unread',
 * `forget` int(2) DEFAULT NULL,
 * `pass` int(1) DEFAULT NULL,
 * `review` int(2) DEFAULT NULL,
 * `review_date` datetime DEFAULT NULL COMMENT 'next review date',
 * `study_date` datetime DEFAULT NULL COMMENT 'the date should learn this word',
 * `dict` int(1) DEFAULT NULL,
 * `sequence` int(1) DEFAULT NULL,
 * PRIMARY KEY (`id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * */
class WordsPool extends NotORM
{
    protected function getTableName($id)
    {
        return 'words_pool';
    }

    /**
     * @param $openid
     * @param int $book dictionary id
     * @return bool|int
     */
    public function updateWordsPool($openid, $book)
    {
        $updateData = array('status' => 0, 'review' => 0, 'review_date' => null, 'study_date' => null);
        $rows = $this->getORM()
            ->where('openid', $openid)
            ->where('dict', $book)
            ->update($updateData);
        if (!$rows) {
            $words = new Words();
            $data = $words->getBookInertWords($openid, $book);
            $rows = $this->getORM()->insert_multi($data);
        }
        return $rows;
    }

    /** Get new words by date
     *
     * @param $openid
     * @param $date
     * @return mixed
     */
    public function getNewWords($openid, $date){
        $schedule = new Schedule();
        $dict = $schedule->getDict($openid);
        $today = date('Y-m-d');
        $time = date('H:i');
        //TODO reset function : no study records for 3 days -> reset
        $words=array();
        if (!$date){
            $history = new History();
            $history = $history->history($openid,$dict,$today);
            //no history today by now || already learnt at morning , but none at afternoon
            if(!$history || ($history==1 && $time >= '13:05')){
                //select 10 new words
                $sql = 'SELECT pool.id,w.content,w.pronunciation,w.audio,w.cndf,w.endf,w.example  '
                    . 'FROM words_pool AS pool LEFT JOIN words AS w '
                    . 'ON pool.word_id = w.id '
                    . 'WHERE pool.openid = :openid and pool.status = 0 and (pool.dict = :dict or pool.dict = 0)  and pool.pass = 0 '
                    . 'ORDER BY pool.sequence desc,pool.dict asc, pool.id asc '
                    . 'LIMIT 10';
                $words = $this->getORM()->queryAll($sql, array(':openid'=>$openid,':dict'=>$dict));
            }
        }else{// return the words by date
            $words = $this->getORM()
                ->where('openid = ? and status = 1 ', $openid)
                ->where('dict = ? or dict = 0', $dict)
                ->where('study_date ', $date)
                ->fetchAll();
        }
        return $words;
    }

    /** check in
     * @param $openid
     * @param array $data
     * @return bool
     */
    public function checkin($openid,$data){
        try{
            if($data){
                $today=date('Y-m-d');
                $this->TransactionBegin();
                if(isset($data['pass'])){
                    $ids=$data['pass'];
                    $this->getORM()->where('id',$ids)->update(array('pass'=>1,'update_date'=>$today,'review_date'=>$today));
                }
                if(isset($data['mark'])){
                    $ids=$data['mark'];
                    $this->getORM()->where('id',$ids)->update(array('mark'=>1,'update_date'=>$today));
                }
                if(isset($data['id'])){
                    $ids=$data['id'];
                    $time=date('H:i:s');
                    $a = $time>'13:05' ? 2:1;

                    $update_arr=array('status'=>1,'study_date'=>$today,'review_date'=>$today,'update_date'=>$today);
                    $this->getORM()
                        ->where("pass!=1")
                        ->where("id",$ids)
                        ->update($update_arr);

                    $schedule=new Schedule();
                    $dict=$schedule->getDict($openid);
                    $history_data=array('openid'=>$openid,'date'=>$today,'time'=>$time,'dict'=>$dict,'a'=>$a);
                    $history= new History();
                    $history->insert($history_data);

                }
                $this->TransactionCommit();
                return true;
            }else{
                return false;
            }
        }catch (Exception $e){
            $this->TransactionRollback();
            \PhalApi\DI()->logger->log('ErrorCatch', 'checkin' ,json_encode($e) );
            return false;
        }
    }

    public function getReviewWords($openid,$page,$chat=null,$perpage=10){
        $schedule = new Schedule();
        $dict = $schedule->getDict($openid);
        $today = date('Y-m-d');
        $limit=($page-1)*$perpage;

        $sql = 'SELECT pool.id,w.content,w.pronunciation,w.audio,w.cndf,w.endf,w.example,pool.forget ,pool.review '
            . 'FROM words_pool AS pool LEFT JOIN words AS w '
            . 'ON pool.word_id = w.id '
            . 'WHERE pool.openid = :openid and pool.status = 1 and (pool.dict = :dict or pool.dict = 0)  and pool.pass = 0 and (review_date <=:today or update_date=:today)'
            . 'ORDER BY pool.forget desc, pool.sequence desc, pool.id asc ';
        if($chat!='chat'){
            $sql .= 'LIMIT '.$limit.' ,'.$perpage;
        }
        $words = $this->getORM()->queryAll($sql, array(':openid'=>$openid,':dict'=>$dict,':today'=>$today));

        return $words;
    }

    public function review($openid,$data){
        //update review times and next review date
        $review_ids=implode(",",$data['id']);
        try{
            $today = date('Y-m-d');
            $this->TransactionBegin();
            if(isset($data['forget'])){
                $ids=$data['forget'];
                $this->getORM()->where('id',$ids)->where('review_date<=:today and openid=:openid ',array(':today'=>$today,':openid'=>$openid))->update(array('forget'=>new \NotORM_Literal("forget + 1"),'update_date'=>$today));
            }
            if(isset($data['mark'])){
                $ids=$data['mark'];
                $this->getORM()->where('id',$ids)->where('review_date<=:today and openid=:openid ',array(':today'=>$today,':openid'=>$openid))->update(array('mark'=>1,'update_date'=>$today));
            }
            $update_sql='UPDATE words_pool set update_date=:today ,review_date= '
                .' CASE WHEN ( review = 0 or review = 1 or review = 2 ) THEN date_add(:today,interval review+1 day) '
                .' WHEN review=3 then date_add(review_date,interval 8 day) '
                .' WHEN review=4 then date_add(review_date,interval 15 day) '
                .' ELSE date_add(review_date,interval 30 day) '
                .' END ,review = review+1 '
                .' WHERE id in ('.$review_ids.') and  review_date<=:today and openid=:openid ';
            $this->getORM()->queryAll($update_sql, array(':ids'=>$review_ids,':today'=>$today,':openid'=>$openid));
            $this->TransactionCommit();
            return true;
        }catch(Exception $e){
            $this->TransactionRollback();
            \PhalApi\DI()->logger->log('ErrorCatch', 'review' ,json_encode($e) );
            return false;
        }
    }

    public function totalReview($openid){
        $schedule=new Schedule();
        $dict=$schedule->getDict($openid);
        $today=date('Y-m-d');
        $total=$this->getORM()
            ->select('id')
            ->where('openid = :openid and status = 1 and (dict = :dict or dict = 0)  and pass = 0 and (review_date <=:today or update_date=:today)',array(':openid'=>$openid,':dict'=>$dict,':today'=>$today))
            ->count('id');
        return $total;
    }
}