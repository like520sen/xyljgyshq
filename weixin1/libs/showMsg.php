<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/5/23
 * Time: 17:06
 */

class showMsg {
    public static function showResponse($content){
        echo json_encode($content);
        return;
    }

    public static function showErrorCode($code){
        echo json_encode(array("code"=>$code));
        return;
    }
}