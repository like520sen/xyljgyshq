<?php

require_once dirname(__FILE__).'/../libs/showMsg.php';
require_once dirname(__FILE__) . '/../Model/RegisterDB.php';
require_once dirname(__FILE__) . '/../libs/config.php';

$registerInfo=new registerInfo();
$registerInfo->router();
class registerInfo{
    public function router(){
        $reqType=$_SERVER['REQUEST_METHOD'];
        if($reqType=="POST"){

            exit();
        }else{
            $this::getRegisterInfo();
            exit();
        }
    }

    private function getRegisterInfo(){
        //        $openid= $_SESSION['openid']==""?config::openid:$_SESSION['openid'];

        $openid=config::openid;
        if(empty($openid)){
            showMsg::showResponse(array("code"=>403));
        }

        $registerDB=new RegisterDB();
        $registerInfoList= $registerDB->getRegisterInfoList($openid);

        if($registerInfoList!=false){
            $responseArray=array("code"=>200,"registerInfos"=>array());
            foreach($registerInfoList as $key=>$value){
                $registerInfos=array(
                    "registerID"=>$value[RegisterDB::register_id_filed],
                    "roomID"=>$value[RegisterDB::building_field]."-".$value[RegisterDB::floor_field]."-".$value[RegisterDB::room_number_field],
                    "name"=>$value[RegisterDB::name_field],
                    "tel"=>$value[RegisterDB::tel_field],
                    "qq"=>$value[RegisterDB::qq_field],
                    "memo"=>$value[RegisterDB::memo_field],
                    "permission"=>$value[RegisterDB::permission_field],
                    "checked"=>$value[RegisterDB::checked_field],
                    "createTime"=>$value[RegisterDB::create_time_field],
                );
                array_push($responseArray['registerInfos'],$registerInfos);
            }
            showMsg::showResponse($responseArray);
            exit();
        }else{
            $responseArray=array("code"=>200,"registerInfos"=>array());
            showMsg::showResponse($responseArray);
            exit();
        }

    }
}