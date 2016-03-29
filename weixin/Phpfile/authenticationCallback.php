<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/5/23
 * Time: 11:51
 * 微信第三方认证登录回调接口
 */
require_once dirname(__FILE__).'/../libs/config.php';
require_once dirname(__FILE__).'/../libs/showMsg.php';

$authentication=new authenticationCallback();
$authentication->router();
class authenticationCallback{
    public function router(){
        $reqType=$_SERVER['REQUEST_METHOD'];
        if($reqType=='GET'){
            $code=isset($_GET['code'])?$_GET['code']:"";
            if($code==''){
                //没有经过授权，非法请求
//                showMsg::showErrorCode(403);
                showMsg::showErrorCode("code is null");
                exit();
            }

//            ///获取请求的动作标志码
//            $action=isset($_GET['action'])?$_GET['action']:1;
//            $_SESSION['action']=$action;
            //获取openID
            var_dump($code);
            $openid=$this->getOpenID($code);
            var_dump($openid);
            if($openid==false){
                //没有经过授权，非法请求
//                showMsg::showErrorCode(403);
                showMsg::showErrorCode("openid is null");
                exit();
            }

            echo "成功";
            //将该访问者的openid放入SESSION中
            $_SESSION['openid']=$openid;

            //分发不同的动作路由
//            switch($action){
//                case 1:{
//                    //报名入口
//                    $this->registerEntry($openid);
//                    exit();
//                }
//                case 2:{
//                    exit();
//                }
//                case 3:{
//                    exit();
//                }
//                case 4:{
//                    exit();
//                }
//                default:{
//                    showMsg::showErrorCode(401);
//                    exit();
//                }
//            }
        }else{
            showMsg::showErrorCode(401);
            exit();
        }
    }

    /*
     * 检查此用户报名状态
     */
    private function registerEntry($openid){
        require_once dirname(__FILE__) . '/../Model/RegisterDB.php';
        require_once dirname(__FILE__) . '/../Model/RegisterDB.php';

        $user =new UserDB();
        //判断该用户是否注册
        if($user->isUserExist($openid)){
            //查询用户是否绑定报名信息
            $register=new RegisterDB();
            if($register->isExistByOpenid($openid)){
                //绑定了，返回用户报名信息
                header("Location:../HtmlFile/registerInfo.html");
            }else{
                //未绑定，跳入报名界面
                header("Location:../HtmlFile/register.html");
            }
        }else{
            //未注册，跳入输入手机号查询报名界面
            header("Location:../HtmlFile/bindTel.html");
        }
    }


    /*
     * 签到系统入口
     * @Param:          微信openID
     */
    private function signatureOperation($openID){
//        require_once dirname(__FILE__).'/../libs/Employee.class.php';
//        ///检查识别是否老员工
//        if(Employee::CheckOpenIDExist($openID)){
//            ///openID存在，存在该员工
//            $employee=new Employee();
//            $employee=$employee->getEmployeeObjectByOpenID($openID);
//            ////将员工ID号放入SESSION中
//            if($employee!=false){
//                $_SESSION['ID']=$employee->ID;
//                //获取今日签到记录
//                require_once dirname(__FILE__).'/../libs/SignRecord.class.php';
//                $signRecord=new signRecord();
//                $year=date('Y');
//                $month=date('n');
//                $day=date('d');
//                $signRecord=$signRecord->GetSignRecordObjectByDate($employee->ID,$year,$month,$day);
//                if($signRecord==false){
//                    ///今天还没有签到，返回签到页面
//                    header("Location:../signviews/home.html");
//                }elseif($signRecord->signOut==""){
//                    ///已经签到，返回签退页面
//                    header("Location:../signviews/home.html");
//                }else{
//                    ///已经签退，返回这个月签到记录列表页面
//                    header("Location:../signviews/home.html");
//                }
//            }
//        }else{
//            ///检查是否是新加入的员工在注册
//            //将该访问者的openID放入SESSION中
//            $_SESSION['openID']=$openID;
//            //返回申请成为员工页面
//            header("Location:../signviews/apply.html");
//        }
    }

    /*
     * 校园代理入口
     * @Param:          微信openID
     */
    private function schoolAgentOperation($openID){
//        $_SESSION[config::SESSION_OPENID]=$openID;
//        require_once dirname(__FILE__).'/../libs/schoolAgent.php';
//        $schoolAgentID=schoolAgent::getSchoolAgentIDByOpenID($openID);
//        if($schoolAgentID==false){
//            //新代理注册
//            header("Location:../schoolAgent/mobile/register.html");
//        }else{
//            //主页面
//            $_SESSION['agentID']=$schoolAgentID;
//            header("Location:../schoolAgent/mobile/info.html");
//        }
    }

    /*
     * 私有方法
     */
    //===============================================================================================================================
    ///获取openID
    private function getOpenID($code){
        $appID=config::appId;
        $secret=config::secret;
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appID&secret=$secret&code=$code&grant_type=authorization_code";
        $data=$this->httpsRequest($url);
        $data=json_decode($data);
        if(array_key_exists('openid',$data)){
            $openID=$data->openid;
            return $openID;
        }else{
            showMsg::showResponse(array("code"=>403));
            return false;
        }
    }

    ///https的请求
    private function httpsRequest($url,$post=false){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        if($post){
            curl_setopt($curl,CURLOPT_PORT,1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
        }
        $data=curl_exec($curl);
        curl_close($curl);
        return $data;
    }
}