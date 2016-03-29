<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/7/20
 * Time: 17:58
 */
//require_once dirname(__FILE__).'/../libs/config.php';
require_once dirname(__FILE__).'/../libs/showMsg.php';

$login=new login();
$login->router();
class login{
    public function router(){
        $reqType=$_SERVER['REQUEST_METHOD'];
        if($reqType=="POST"){
            $this->checkLogin();
        }elseif($reqType=='GET'){
            $this->GetLoadInfo();
        }else{
            showMsg::showErrorCode(401);
            exit();
        }
    }

    private function checkLogin(){
        $userName=isset($_POST['userName'])?$_POST['userName']:"";
        $password=isset($_POST['password'])?$_POST['password']:"";
        if($userName!=""&&$password!=""){
            require_once dirname(__FILE__) . '/../Model/UserDB.php';
            $checked=user::checkLogin($userName,$password);
            echo $checked;
            if($checked){
                echo "yes";
                header("location:register.html");
                exit();
            }else{
                header("location:index.html");
                exit();
            }
        }
        else{
            echo "no";
//            header("location:index.html");
//            exit();
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