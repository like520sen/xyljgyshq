<?php

require_once dirname(__FILE__).'/../libs/showMsg.php';
require_once dirname(__FILE__) . '/../Model/RegisterDB.php';
require_once dirname(__FILE__) . '/../libs/config.php';

$register=new register();
$register->router();
class register{
    public function router(){
        $reqType=$_SERVER['REQUEST_METHOD'];
        if($reqType=="POST"){
            $this::registerUser();
            exit();
        }else{
            showMsg::showResponse(array("code"=>401));
            exit();
        }

//        $this::registerUser();

//
//        $reqType=$_SERVER['REQUEST_METHOD'];
//        if($reqType=="POST"){
//            $this->registerUser();
//        }elseif($reqType=='GET'){
//            $this->GetLoadInfo();
//        }else{
//            showMsg::showErrorCode(401);
//            exit();
//        }
    }

    private function registerUser(){
//        $openid= $_SESSION['openid']==""?config::openid:$_SESSION['openid'];

        $openid=config::openid;
        if(empty($openid)){
            showMsg::showResponse(array("code"=>403));
        }

        $register=new RegisterDB();
        $register->openid=$openid;
        $register->name=isset($_POST['name'])?$_POST['name']:"";
        $register->building=isset($_POST['building'])?(int)$_POST['building']:0;
        $register->floor=isset($_POST['floor'])?(int)$_POST['floor']:0;
        $register->roomNumber=isset($_POST['roomNumber'])?(int)$_POST['roomNumber']:0;
        $register->tel=isset($_POST['tel'])?$_POST['tel']:"";
        $register->qq=isset($_POST['qq'])?$_POST['qq']:"";
        $register->permission=$_POST['permission']=="on"?1:0;
        $register->createTime=date('Y-m-d H:i:s');
        $register->checked=0;
        $register->memo=isset($_POST['memo'])?$_POST['memo']:"";

        $registerID=$register->add($register);
//        var_dump($registerID);

        if($registerID>0){
            header("Location:registerInfo.html");
        }else{
            showMsg::showResponse(array("code"=>503));
        }
    }

    private function GetLoadInfo(){
        if((isset($_SESSION[config::manager_session_loginField])&&($_SESSION[config::manager_session_loginField]==true))){
            if($_SESSION[config::manager_manager_sessionAuthority_Field]==config::manager_manager_AuthorityLevel_SuperAdmin){
                $level=0;
            }else{
                $level=1;
            }
            showMsg::showResponse(array("code"=>200,"userName"=>$_SESSION[config::manager_session_userNameField],"level"=>$level));
            exit();
        }else{
            showMsg::showResponse(array("code"=>403,"url"=>'index.html'));
            exit();
        }
    }
}