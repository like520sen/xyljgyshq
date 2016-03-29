/**
 * Created by sen on 2016/3/2.
 */

//检查输入是否满足要求
function checkRegister() {
    //输入不能为空
    var registerForm=document.registerForm;
    var building=registerForm.building.value;
    var floor=registerForm.floor.value;
    var roomNumber=registerForm.roomNumber.value;
    var name=registerForm.name.value;
    var tel=registerForm.tel.value;
    var qq=registerForm.qq.value;
    var shareLimit=registerForm.shareLimit.value;
    var memo=registerForm.memo.value;

    if(building==""||floor=="" ||roomNumber==""||name=="" ||tel==""){
        alert("请输入正确格式，并准确填写申请信息！");
    }else{
        //调用php服务
        //alert("ok");
        addRegister();
    }
}

//添加申请登记
function addRegister(){
    var data = $(document.registerForm).serializeArray();
    $.ajax({
        url:'../HtmlFile/register.php',
        type:'POST',
        data:data,
        success: function (data) {
            var code = JSON.parse(data).code;
            switch (code){
                case 401:
                    alert('未登录');
                    break;
                case 403:
                    alert('禁止访问');
                    break;
                case 503:
                    alert('操作失败');
                    break;
                case 200:
                    window.location.href="http://www.baidu.com";
                    break;
                case 801:
                    alert('系统错误'+data.content);
                    break;
                default :
                    alert('未知错误');
            }
            return false;
        },
        error : function (){
            alert('apply server return err!');
            return false;
        }
    });
}

//验证字符串是否全是数字：1全是，0不全是。
function check_validate_number(value){
    var reg = /^[0-9a-zA-Z]+$/;
    return reg.test(value);
}