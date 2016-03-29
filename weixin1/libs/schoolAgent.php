<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/7/19
 * Time: 7:29
 * 类说明：校园代理账户类
 */
require_once dirname(__FILE__) . '/../libs/config.php';
require_once dirname(__FILE__) . '/../libs/medoo.php';

class schoolAgent {
    /*
     * 数据库中schoolAgent_account表的配置
     */
    //================================================================
    const tableName='schoolagent_account';
    const Agent_ID_Field='Agent_ID';
    const Agent_OpenID_Field='Agent_OpenID';
    const Agent_Name_Field='Agent_Name';
    const Agent_Tel_Field='Agent_Tel';
    const Agent_CreateTime_Field='Agent_CreateTime';
    //=================================================================

    /*
     * 公有属性
     */
    //==================================================================
    public $Agent_ID;
    public $Agent_OpenID;
    public $Agent_Name;
    public $Agent_Tel;
    public $Agent_CreateTime;
    //==================================================================

    /*
     * 公有方法
     */
    //===================================================================

    /*
     *添加校园大使账号
     * @Param:          schoolAgent             校园代理对象
     * @return:         true||false             操作成功||失败
     */
    public function addSchoolAgent(schoolAgent $schoolAgent){
        $database=new medoo();
        $id=$this->createRandomID();
        $lastID=$database->insert(self::tableName,array(
            self::Agent_ID_Field=>$id,
            self::Agent_Name_Field=>$schoolAgent->Agent_Name,
            self::Agent_OpenID_Field=>$schoolAgent->Agent_OpenID,
            self::Agent_Tel_Field=>$schoolAgent->Agent_Tel,
            self::Agent_CreateTime_Field=>date('Y-m-d H:i:s')
        ));
        if($lastID>0){
            return true;
        }else{}
        return false;
    }

    /*
     * 根据openID查找代理对象的ID号
     * @Param:          openID                       微信第三方授权码
     * @return:         schoolAgentID||false         代理ID号||错误
     */
    public static function getSchoolAgentIDByOpenID($openID){
        $database=new medoo();
        $schoolAgentArray=$database->select(self::tableName,array(self::Agent_ID_Field),array(self::Agent_OpenID_Field=>$openID));
        if($schoolAgentArray!=false){
            return $schoolAgentArray[0][self::Agent_ID_Field];
        }else{
            return false;
        }
    }

    /*
     * 根据代理的ID号获取代理对象
     * @Param:          schoolAgentID               代理的ID号
     * @return:         schoolAgent||false          代理对象||false
     */
    public static function getSchoolAgentByID($schoolAgentID){
        $database=new medoo();
        $schoolAgentArray=$database->select(self::tableName,'*',array(self::Agent_ID_Field=>$schoolAgentID));
        if($schoolAgentArray!=false){
            $schoolAgent=new schoolAgent();
            $schoolAgent->Agent_ID=$schoolAgentArray[0][self::Agent_ID_Field];
            $schoolAgent->Agent_Name=$schoolAgentArray[0][self::Agent_Name_Field];
            $schoolAgent->Agent_OpenID=$schoolAgentArray[0][self::Agent_OpenID_Field];
            $schoolAgent->Agent_Tel=$schoolAgentArray[0][self::Agent_Tel_Field];
            $schoolAgent->Agent_CreateTime=$schoolAgentArray[0][self::Agent_CreateTime_Field];
            return $schoolAgent;
        }else{
            return false;
        }
    }

    /*
     * 根据ID获取代理名字
     */
    public static function getAgentNameByID($schoolAgentID){
        $database=new medoo();
        $schoolAgentArray=$database->select(self::tableName,'*',array(self::Agent_ID_Field=>$schoolAgentID));
        if($schoolAgentArray!=false){
            return $schoolAgentArray[0][self::Agent_Name_Field];
        }else{
            return false;
        }
    }


    /*
     * 获取校园代理列表
    *  @Param:           page              页码
     * @Param:          pageCount          每一页条数
    */
    public static function getSchoolAgents($page=1,$pageCount=10){
        $database=new medoo();
        $schoolAgents=$database->select(self::tableName,'*',array(
            "LIMIT"=>$page*$pageCount
        ));
        if($schoolAgents!=false){
            $schoolAgentArray=array();
            $index=0;
            for($i=($page-1)*$pageCount;$i<count($schoolAgents);$i++){
                $schoolAgentArray[$index]=$schoolAgents[$i];
                $index++;
            }
            return $schoolAgentArray;
        }else{
            return false;
        }
    }

    public static function getSchoolAgentCount($pageCount=10){
        $database=new medoo();
        $schoolAgentCount=$database->count(self::tableName);
        return ceil($schoolAgentCount/$pageCount);
    }

    public static function deleteSchoolAgent($agentID){
        $database=new medoo();
        $rows=$database->delete(self::tableName,array(self::Agent_ID_Field=>$agentID));
        if($rows>0){
            return true;
        }else{
            return false;
        }
    }

    //===================================================================


    /*
     * 私有方法
     */
    //===================================================================
    private function createRandomID(){
        $ID=date('YmdHis');
        for($i=0;$i<3;$i++){
            $rnd=rand(1,999);
            if(strlen($rnd)==1){
                $ID=$ID."00".$rnd;
            }elseif(strlen($rnd)==2){
                $ID=$ID."0".$rnd;
            }else{
                $ID=$ID.$rnd;
            }
        }
        return $ID;
    }
    //=====================================================================
}