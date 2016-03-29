<?php
/**
 * Created by PhpStorm.
 * User: sen
 * Date: 2016/2/25
 * Time: 15:38
 */
require_once dirname(__FILE__) . '/../Model/medoo.php';

class UserDB
{
    /*
 * 数据库表配置
 */
    //=============================================
    const TableName="user";
    const user_id_field="user_id";
    const openid_field="openid";
    const tel_field="tel";
    const nickname_field="nickname";
    const sex_field="sex";
    const province_field="province";
    const city_field="city";
    const country_field="country";
    const headimgurl_field="headimgurl";
    const privilege_field="privilege";
    const unionid_field="unionid";
    const subscribe_time_field="subscribe_time";

    /*
     * 公有属性
     */
    //=============================================
    public $userId;
    public $openid;
    public $tel;
    public $nickname;
    public $sex;
    public $province;
    public $city;
    public $country;
    public $headimgurl;
    public $privilege;
    public $unionid;
    public $subscribe_time;

    /*
     * 公有方法
     */
    //==========================================================

    //添加用户
    public function add($openid){
        $database=new medoo();
        $newUser=new UserDB();
        if(isset($openid)&&isset($tel)){
            $newUserID=$database->insert(self::TableName,[self::openid_field=>$newUser->openid]);
            if($newUserID>0){
                return $newUserID;
            }
        }
        return 0;
    }

    public function createUser($openid,$tel){
        $database=new medoo();
        if(isset($openid)&&isset($tel)){
            if(!self::isUserExist($openid)){
                $newUserID=$database->insert(self::TableName,[self::openid_field=>$openid,self::tel_field=>$tel]);
                if($newUserID>0){
                    return $newUserID;
                }
            }
        }
        return 0;
    }

    //根据openid检查用户是否存在
    public function isUserExist($openid){
        $database=new medoo();
        return $database->has(self::TableName, [self::openid_field=>$openid]);
    }

    public static function checkLogin($userName,$password){
//        $database=new medoo();
//        $pw=$database->select(self::tableName,'*',array(
//            "LIMIT"=>$page*$pageCount
//        ));
//
//        $database = new medoo();
//
//        $datas = $database->select("user", ["password"], ["userName" => $userName]);
//        if(count($datas)==1){
//            foreach($datas as $data)
//            {
//                if($password==$data["password"]){
//                    return true;
//                }else{
//                    return false;
//                }
//            }
//        }else{
//            return false;
//        }
        return true;
//        echo "start";
////        $database=new medoo();
////        $users=$database->select(self::TableName,'*',array(
////            self::UserName_Field=>$userName
////        ));
//        echo count($datas);
////        // 插入数据示例
////        $database->insert(self::TableName, [
////            'userName' => 'foo',
////            'password' => '1234',
////            'sex' => 1,
////            'memo' => "haha"
////        ]);
//
//        return true;

    }
}

