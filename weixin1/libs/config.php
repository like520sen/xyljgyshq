<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2015/5/23
 * Time: 11:50
 */

header("Content-Type:application/json;charset=utf-8");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:POST,OPTIONS,GET,PUT,DELETE");
date_default_timezone_set("PRC");
session_start();
//require_once dirname(__FILE__).'/errHandler.php';

class config{

    /*
     *未认证前自定义openid
     */
    const openid="qwert1";

    /*
     *  微信公众号模块
     */
    const appId="wxb7e36a1bca13adb4";
    const secret="18c625c0fda92b2f2dbc7591345cc740";

    /*
     * 签到模块
     */
    ///网页调试用
    const webDebug=true;
    const defaultEmployeeID='20150527140900051014';
    //申请员工认证接口===========================
    ///申请员工认证接口开关状态
    const applyEmployeeCertificationSwitch=true;
    //认证秘钥
    const signKye="jdyl";
    //===========================================
    //签到签退接口===============================
    ///签到接口开关状态
    const employeeSignSwitch=true;
    ///

    /*
   * SAEStorage模块
   */
    ////乐借存储在saeStore数据URL前缀
    const saeStorePreUrl="http://micmsgjdyl-jdyl.stor.sinaapp.com/";
    const domain="jdyl";
    const imageEmployeePath="employeeImg/";
    const imageEmployeeExt=".png";

    /*
     * 管理员模块
     */
    //===============================================================
    ///SESSION中登陆字段名
    const manager_session_loginField="login";
    ///SESSION中登陆用户名字段名
    const manager_session_userNameField="userName";
    ///SESSION中管理员登录后权限等级
    const manager_manager_sessionAuthority_Field='authority';
    ///管理权限等级
    const manager_manager_AuthorityLevel_SuperAdmin='superAdmin';
    const manager_manager_AuthorityLevel_Admin='Admin';

    const SESSION_OPENID="openid";
}