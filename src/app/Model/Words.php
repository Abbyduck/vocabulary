<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
CREATE TABLE `words` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `content` varchar(45) NOT NULL,
    `pronunciation` varchar(45) DEFAULT NULL,
    `audio` varchar(100) DEFAULT NULL,
    `cndf` varchar(255) DEFAULT NULL,
    `endf` varchar(255) DEFAULT NULL,
    `example` text,
    `shanbay_id` int(11) DEFAULT NULL,
    `dict` int(3) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

 * */

class Words extends NotORM {
    public function getBookInertWords($openid,$book){
        $words=$this->getORM()->select("id as word_id , '.$openid.' as openid ,dict")->where('dict',$book)->fetchAll();
        return $words;

    }
}