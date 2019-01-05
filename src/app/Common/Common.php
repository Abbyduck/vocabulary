<?php
/**
 * Created by PhpStorm.
 * User: Abby
 * Date: 2018/12/7
 * Time: 12:03
 */

namespace App\Common;


class Common
{
    function render($temple, $arr){
        extract($arr);
        ob_start();
        include $temple;
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
        exit();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}