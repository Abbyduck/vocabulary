<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
CREATE TABLE `user` (
`openid` varchar(45) NOT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `sex` varchar(45) DEFAULT NULL,
  `follow` int(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * */

class User extends NotORM {
    public function save($data) {
        if($data){
            $unique = array('openid' => $data['openid']);
            $insert = $data;
//            $update = array('create_time' => date('y-m-d H:i:s'),'follow'=>1,'nickname'=>$data['nickname']);
            $update = array('create_time' => date('Y-m-d H:i:s'),'follow'=>1);
            $row = $this->getORM()->insert_update($unique, $insert, $update);
        }
        return !empty($row)?$row:'';
    }
    public function del($openid) {
        if($openid){
            $row = $this->getORM()->where('openid',$openid)->update(array('follow'=>0));
        }
        return !empty($row)?$row:'';
    }
    public function getNameByOpenid($id) {
        $row = $this->getORM($id)->select('nickname')->fetchRow();
        return !empty($row) ? $row['nickname'] : '';
    }
    public function updateUserByOpenid($openid,$data) {
        $row = $this->getORM()->where('openid',$openid)->update($data);
        return $row;
    }

}