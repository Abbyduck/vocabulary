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
    }
}