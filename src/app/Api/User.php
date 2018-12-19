<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\User as DomainUser;

/**
 * 用户模块接口服务
 */
class User extends Api {

    public function getRules() {
        return array(
            'insert' => array(
                'openid' => array('name' => 'openid', 'require' => true, 'desc' => 'openid'),
                'nickname' => array('name' => 'nickname', 'desc' => '昵称'),
                'sex' => array('name' => 'sex', 'type' => 'int', 'default' => 0, 'desc' => '性别'),
                'follow' => array('name' => 'follow', 'type' => 'int', 'default' => 1, 'desc' => '状态'),
                'create_time' => array('name' => 'follow', 'type' => 'int', 'default' => date("y-m-d H:i:s"), 'desc' => 'follow时间'),
            ),
            'update' => array(
                'nickname' => array('name' => 'nickname', 'desc' => '昵称'),
                'sex' => array('name' => 'sex', 'type' => 'int', 'default' => 0, 'desc' => '性别'),
                'follow' => array('name' => 'follow', 'type' => 'int', 'default' => 1, 'desc' => '状态'),
            ),
            'get' => array(
                'openid' => array('name' => 'openid', 'require' => true, 'desc' => 'openid'),
            ),
            'delete' => array(
                'openid' => array('name' => 'openid', 'require' => true, 'desc' => 'openid'),
            )
        );
    }

    /**
     * 插入数据
     * @desc 向数据库插入一条纪录数据
     * @return string openid
     */
    public function insert() {
        $rs = array();

        $newData = array(
            'openid' => $this->title,
            'nickname' => $this->content,
            'state' => $this->state,
        );

        $domain = new DomainCURD();
        $id = $domain->insert($newData);

        $rs['id'] = $id;
        return $rs;
    }

    /**
     * 更新数据
     * @desc 根据ID更新数据库中的一条纪录数据
     * @return int code 更新的结果，1表示成功，0表示无更新，false表示失败
     */
    public function update() {
        $rs = array();

        $newData = array(
            'title' => $this->title,
            'content' => $this->content,
            'state' => $this->state,
        );

        $domain = new DomainCURD();
        $code = $domain->update($this->id, $newData);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 获取数据
     * @desc 根据ID获取数据库中的一条纪录数据
     * @return int      id          主键ID
     * @return string   title       标题
     * @return string   content     内容
     * @return int      state       状态
     * @return string   post_date   发布日期
     */
    public function get() {
        $domain = new DomainCURD();
        $data = $domain->get($this->id);

        return $data;
    }

    /**
     * 删除数据
     * @desc 根据ID删除数据库中的一条纪录数据
     * @return int code 删除的结果，1表示成功，0表示失败
     */
    public function delete() {
        $rs = array();

        $domain = new DomainCURD();
        $code = $domain->delete($this->id);

        $rs['code'] = $code;
        return $rs;
    }

    /**
     * 获取分页列表数据
     * @desc 根据状态筛选列表数据，支持分页
     * @return array    items   列表数据
     * @return int      total   总数量
     * @return int      page    当前第几页
     * @return int      perpage 每页数量
     */
    public function getList() {
        $rs = array();

        $domain = new DomainCURD();
        $list = $domain->getList($this->state, $this->page, $this->perpage);

        $rs['items'] = $list['items'];
        $rs['total'] = $list['total'];
        $rs['page'] = $this->page;
        $rs['perpage'] = $this->perpage;

        return $rs;
    }
} 
