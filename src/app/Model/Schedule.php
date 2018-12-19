<?php
namespace App\Model;

use PhalApi\Logger;
use PhalApi\Model\NotORMModel as NotORM;

/**
CREATE TABLE `schedule` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `openid` varchar(45) NOT NULL,
    `learning_dict` int(1) NOT NULL DEFAULT '1',
    `create_time` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 * */

class Schedule extends NotORM {

    public function checkSchedule($openid,$book) {
        $oldSchedule=$this->getORM()->where('openid = ? and learning_dict =?',$openid,$book)->fetchOne();
        if($oldSchedule){
            return false;
        }else{
            return true;
        }
    }
    public function save($openid,$book) {
        $unique = array('openid' => $openid);
        $insert = array('openid'=>$openid,'learning_dict'=>$book,'create_time' => date('Y-m-d H:i:s'),);
        $update = array('create_time' => date('Y-m-d H:i:s'),'learning_dict'=>$book);
        $row = $this->getORM()->insert_update($unique, $insert, $update);
        \PhalApi\DI()->logger->info('schedule',$row);
        return !empty($row)?$row:'';
    }

    public function getDict($openid){
        $schedule=$this->getORM()->select('learning_dict')->where('openid = ?',$openid)->fetchOne();
        if($schedule){
            return $schedule['learning_dict'];
        }else{
            return false;
        }
    }
}