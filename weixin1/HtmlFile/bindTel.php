<?php
require_once dirname(__FILE__).'/../libs/showMsg.php';
require_once dirname(__FILE__) . '/../Model/RegisterDB.php';
require_once dirname(__FILE__) . '/../Model/UserDB.php';
require_once dirname(__FILE__) . '/../libs/config.php';

$bindTel=new bindTel();
$bindTel->router();
class bindTel{
    public function router(){
        $reqType=$_SERVER['REQUEST_METHOD'];
        if($reqType=="POST"){
            $this->isExist();
        }elseif($reqType=='GET'){
//            header("location:registerInfo.html");
            showMsg::showErrorCode(403);
            exit();
        }else{
            showMsg::showErrorCode(401);
            exit();
        }
    }

    //用户是否存在，即是否绑定手机号
    private function isExist(){
        //获取SESSION中存储的openid
        $openid=isset($_SESSION['openid'])?$_SESSION['openid']:"";
        $openid=$openid==""?config::openid:$openid;

        if($openid==""){
            showMsg::showResponse(array("code"=>601));
            exit();
        }

        $tel=isset($_POST['tel'])?$_POST['tel']:"";

        if($tel==""){
            header("location:bindTel.html");
            exit();
        }

        $user=new UserDB();
        $userId=$user->createUser($openid,$tel);

        if($userId>0){//新建用户成功
            //判断是否已经报名
            $register=new RegisterDB();
            if($register->isExistByTel(($tel))){
                //已经报名，将报名信息与openid关联
                if($register->bindOpenid($openid,$tel)>0){
                    header("Location:registerInfo.html");
                }else{
                    //绑定报名信息失败，操作失败
                    showMsg::showResponse(array("code"=>503));
                }
            }else{
                //未报名
                header("Location:register.html");
            }
        }else{//新建用户失败，操作失败
            showMsg::showResponse(array("code"=>503));
        }

//        $userName=isset($_POST['userName'])?$_POST['userName']:"";
//        $password=isset($_POST['password'])?$_POST['password']:"";
//        if($userName!=""&&$password!=""){
//            require_once dirname(__FILE__) . '/../Model/UserDB.php';
//            $checked=user::checkLogin($userName,$password);
//            echo $checked;
//            if($checked){
//                echo "yes";
//                header("location:register.html");
//                exit();
//            }else{
//                header("location:index.html");
//                exit();
//            }
//        }
//        else{
//            echo "no";
////            header("location:index.html");
////            exit();
//        }
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