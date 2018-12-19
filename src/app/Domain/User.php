<?php
namespace App\Domain;

use App\Model\User as ModelUser;

class User {

    public function insert($newData) {
        $newData['create_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        $model = new ModelUser();
        return $model->save($newData);
    }
    public function delete($id) {
        $model = new ModelUser();
        return $model->del($id);
    }

//    public function update($id, $newData) {
//        $model = new ModelUser();
//        return $model->update($id, $newData);
//    }
//
//    public function get($id) {
//        $model = new ModelUser();
//        return $model->get($id);
//    }
//
//
}
