<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
use App\Model\Words;

/**
CREATE TABLE `history` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `openid` varchar(45) DEFAULT NULL,
    `date` date DEFAULT NULL,
    `time` time DEFAULT NULL,
    `dict` int(1) DEFAULT '1',
    `a` int(1) DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='learning history';
 */

class History extends NotORM
{
    /** Get learning history by openid , dictionary id , date
     * @param $openid
     * @param $book
     * @param $date
     * @return mixed
     */
    public function history($openid, $book,$date)
    {
        $rows = $this->getORM()
            ->select('a')
            ->where('openid = ? and dict = ? and date = ?', $openid, $book,$date)
            ->order('id desc')
            ->limit(1)
            ->fetchOne();
        return $rows;
    }

}