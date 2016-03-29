<?php

require_once dirname(__FILE__) . '/../Model/medoo.php';
require_once dirname(__FILE__) . '/../Model/RegisterDB.php';

class RegisterDB
{
    /*
 * 数据库表配置
 */
    //=============================================
    const TableName="register";
    const register_id_filed="register_id";
    const openid_field="openid";
    const name_field="name";
    const building_field="building";
    const floor_field="floor";
    const room_number_field="room_number";
    const tel_field="tel";
    const qq_field="qq";
    const permission_field="permission";
    const create_time_field="create_time";
    const checked_field="checked";
    const memo_field="memo";

    /*
     * 公有属性
     */
    //=============================================
    public $registerID;
    public $openid;
    public $name;
    public $building;
    public $floor;
    public $roomNumber;
    public $tel;
    public $qq;
    public $permission;
    public $createTime;
    public $checked;
    public $memo;

    /*
     * 公有方法
     */
    //==========================================================

    /*
     * 根据openid检查用户是报名
     */
    public function isExistByOpenid($openid){
        $database=new medoo();
        return $database->has(self::TableName, [self::openid_field=>$openid]);
    }

    /*
     * 根据tel检查该电话号码是否报名
     */
    public function isExistByTel($tel){
        $database=new medoo();
        return $database->has(self::TableName, [self::tel_field=>$tel]);
    }

    /*
     * 根据tel关联openid与报名信息
     */
    public function bindOpenid($openid,$tel){
        $database=new medoo();

//        $datas = $database->select(self::TableName,[self::register_id_filed], [self::tel_field => $tel]);
//        if(count($datas)>0){
//            $result=0;
//            foreach($datas as $data) {
//                $result+= $database->update(self::TableName, [self::openid_field => $openid], [self::register_id_filed => $data[self::register_id_filed]]);
//            }
//            if($result>0) return $result;
//        }
//        return false;

        $result= $database->update(self::TableName, [self::openid_field => $openid], [self::tel_field =>$tel]);
        return $result;
    }

    /*
     * 报名
     */
    public function add(RegisterDB $register){
        $database=new medoo();
        //var_dump($database);
        //判断申请的房号是否已经被申请

//        var_dump($register->building);
//        var_dump($register->floor);
//        var_dump($register->roomNumber);
        $datasCount = $database->count(self::TableName, ["AND"=>[self::building_field=>$register->building,
            self::floor_field=>$register->floor,self::room_number_field=>$register->roomNumber]]);
//        var_dump($datasCount);
        if($datasCount>0){
            return 0;
        }
//        $datas = $database->select("user", ["password"], ["userName" => $register->building]);
//        count($datas);
//        if(count($datas)){
//            var_dump(count($datas)s);
//        }
        $register_id=$database->insert(self::TableName,array(
            self::openid_field=>$register->openid,
            self::name_field=>$register->name,
            self::building_field=>$register->building,
            self::floor_field=>$register->floor,
            self::room_number_field=>$register->roomNumber,
            self::tel_field=>$register->tel,
            self::qq_field=>$register->qq,
            self::permission_field=>$register->permission,
            self::create_time_field=>$register->createTime,
            self::checked_field=>$register->checked,
            self::memo_field=>$register->memo
        ));
        return $register_id;
    }

    public function getRegisterInfoList($openid){
        $database=new medoo();

        $datas = $database->select(self::TableName,"*", [self::openid_field => $openid,"ORDER"=> [self::create_time_field.' DESC']]);

        $result=array();
        foreach($datas as $data) {
            $data[self::permission_field]=$data[self::permission_field]==1?"是":"否";
            switch( $data[self::checked_field]){
                case 0: $data[self::checked_field]= "未审核";break;
                case 1: $data[self::checked_field]= "审核通过";break;
                case 2: $data[self::checked_field]= "审核不通过";break;
            }
            array_push($result,$data);
        }
        return $result;
    }
}

