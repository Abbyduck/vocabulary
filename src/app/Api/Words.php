<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Words as DomainWords;

/**
 * Words
 */
class Words extends Api {
    public function getRules() {
        return array(
            'insert' => array(
                'content' => array('name' => 'content', 'require' => true, 'desc' => '单词'),
                'pronunciation' => array('name' => 'pronunciation', 'desc' => '发音'),
                'audio' => array('name' => 'audio', 'desc' => '音频'),
                'cndf' => array('name' => 'cndf', 'desc' => '中译'),
                'endf' => array('name' => 'endf','desc' => '英译'),
                'shanbay_id' => array('name' => 'shanbay_id', 'type' => 'int', 'default' => 0, 'desc' => '扇贝id'),
                'dict' => array('name' => 'dict', 'type' => 'int', 'default' => 0, 'desc' => 'word book'),
            ),
            'getList' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            ),
        );
    }
    /**
     * 插入数据
     * @desc 向数据库插入一条纪录数据
     * @return int id 新增的ID
     */
    public function insert() {
        $rs = array();

        $newData = array(
            'title' => $this->title,
            'content' => $this->content,
            'state' => $this->state,
        );

        $domain = new DomainWords();
        $id = $domain->insert($newData);

        $rs['id'] = $id;
        return $rs;
    }
    /**
     * 批量插入数据
     * @desc 向数据库插入一条纪录数据
     * @return int id 新增的ID
     */
    public function multiInsert() {
        $rs = array();

        $newData = array(
            'title' => $this->title,
            'content' => $this->content,
            'state' => $this->state,
        );

        $domain = new DomainWords();
        $id = $domain->insert($newData);

        $rs['id'] = $id;
        return $rs;
    }

} 
