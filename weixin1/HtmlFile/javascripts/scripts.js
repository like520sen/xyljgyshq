/**
 * Created by Administrator on 2015/7/31.
 */

var host = 'http://66.66.66.101/SAESVN/micmsgjdyl/1/manager/';

$('#left').height($(window).height()-60);
$(window).resize(function () {
    $('#left').height($(window).height()-60);
});
var id;
var uo;
function writeAlert(msg){
    alert(msg);
}

function clearRight(){
    $('#cal').css('display','none');
    $('#pSignList').css('display','none');
    $('#dSignList').css('display','none');
}

function closeWindow(){
    $('#bodyCover').fadeOut();
    $('#signUpdate').fadeOut();
}

$('#bodyCover').click(function () {
    closeWindow();
});

function pSign(){
    $.ajax({
        url:'account/employeeName.php',
        type:'GET',
        success: function (data) {
            if(data.code==201){
                writeAlert('没有数据');
                clearRight();
                $('#pSignList').fadeIn();
                $('#pSignLList').html("");
                $('#pSignDate').text(data.year+'年'+data.month+'月');
            }
            else if(data.code==200){
                $('#pSignDate').text(data.year+'年'+data.month+'月');
                var year = data.year;
                var month = data.month;
                clearRight();
                $('#pSignList').fadeIn();
                $('#pSignLList').html("");
                data.names.forEach(function (data) {
                    $('<div class="pSignLabel">').attr('data-id',data.id).text(data.name).appendTo($('#pSignLList'));
                });
                $('.pSignLabel').click(function () {
                    id = $(this).attr('data-id');
                    $.ajax({
                        url:'account/employeeSign.php',
                        type:'GET',
                        data:{type:0,year:year,month:month,id:id},
                        success: function (data) {
                            if(data.code==200){
                                clearRight();
                                $('#cal').fadeIn();
                                $('#calAvatar').attr('src',data.imgUrl);
                                $('#calName').text(data.name);
                                $('#calDate').text(data.year+'年'+data.month+'月');
                                $('#calContainer').fullCalendar('destroy');
                                $('#calContainer').fullCalendar({
                                    height:500,
                                    header:false,
                                    lang:'zh-cn',
                                    events:data.signRecords,
                                    defaultDate:data.date,
                                    eventColor:'#20C375'
                                });
                            }
                        }
                    })
                })
            }
        }
    });
}

function getPSign(form){
    var data = $(form).serializeArray();
    $.ajax({
        url:'account/employeeName.php',
        type:'GET',
        data:data,
        success: function (data) {
            if(data.code==201){
                writeAlert('没有数据');
                clearRight();
                $('#pSignList').fadeIn();
                $('#pSignLList').html("");
                $('#pSignDate').text(data.year+'年'+data.month+'月');
            }
            else if(data.code==200){
                $('#pSignDate').text(data.year+'年'+data.month+'月');
                var year = data.year;
                var month = data.month;
                $('#pSignLList').html('');
                data.names.forEach(function (data) {
                    $('<div class="pSignLabel">').attr('data-id',data.id).text(data.name).appendTo($('#pSignLList'));
                });
                $('.pSignLabel').click(function () {
                    id = $(this).attr('data-id');
                    $.ajax({
                        url:'account/employeeSign.php',
                        type:'GET',
                        data:{type:0,year:year,month:month,id:id},
                        success: function (data) {
                            if(data.code==201){
                                writeAlert('没有数据！');
                            }
                            clearRight();
                            $('#cal').fadeIn();
                            $('#calAvatar').attr('src',data.imgUrl);
                            $('#calName').text(data.name);
                            $('#calDate').text(data.year+'年'+data.month+'月');
                            $('#calContainer').fullCalendar('destroy');
                            $('#calContainer').fullCalendar({
                                height:500,
                                header:false,
                                lang:'zh-cn',
                                events:data.signRecords,
                                defaultDate:data.date,
                                eventColor:'#20C375'
                            });
                        }
                    })
                })
            }
        }
    });
    return false;
}

function getCal(form){
    var data = $(form).serializeArray();
    var i = {
        name:'id',
        value:id
    };
    var t = {
        name:'type',
        value:0
    };
    data.push(i);
    data.push(t);
    $.ajax({
        url:'account/employeeSign.php',
        type:'GET',
        data:data,
        success: function (data) {
            if(data.code==201){
                writeAlert('没有数据！');
            }
            $('#calAvatar').attr('src',data.imgUrl);
            $('#calName').text(data.name).attr('data-id',id);
            $('#calDate').text(data.year+'年'+data.month+'月');
            $('#calContainer').fullCalendar('destroy');
            $('#calContainer').fullCalendar({
                height:500,
                header:false,
                lang:'zh-cn',
                events:data.signRecords,
                defaultDate:data.date,
                eventColor:'#20C375'
            });
        }
    });
    return false;
}

function dSign(){
    $.ajax({
        url:'account/employeeDaySign.php',
        type:'GET',
        success: function (data) {
            if(data.code==201){
                writeAlert('没有数据！');
                clearRight();
                $('#dSignList').fadeIn();
                $('#dSignContainer').html("");
                $('#dSignDate').text(data.year+'年'+data.month+'月'+data.day+'日');
            }
            else if(data.code==200){
                clearRight();
                $('#dSignList').fadeIn();
                $('#dSignContainer').html('');
                $('#dSignDate').text(data.year+'年'+data.month+'月'+data.day+'日');
                data.signRecords.forEach(function (data) {
                    var sin = data.signIn==''?'无数据':data.signIn;
                    var sout = data.signOut==''?'无数据':data.signOut;
                    $('<div class="dSignBox">').attr('data-id',data.id)
                        .append($('<div class="dSignHeader">').text(data.name))
                        .append($('<div class="dSignInfo">')
                            .append($('<div class="dSignLine">')
                                .append($('<span class="dSignTitle">').text('签到：'+sin))
                                .append($('<span class="update" onclick="update(this,1)">').text('修改')))
                            .append($('<div class="dSignLine">')
                                .append($('<span class="dSignTitle">').text('签退：'+sout))
                                .append($('<span class="update" onclick="update(this,2)">').text('修改'))))
                        .appendTo($('#dSignContainer'));
                })
            }
        }
    })
}

$('#dSignMonth').change(function () {
    $(this).next().html('');
    var m = $(this).val();
    switch(m){
        case '1':
        case '3':
        case '5':
        case '7':
        case '8':
        case '10':
        case '12':
            for(var c = 1;c<=31;c++){
                $('<option>').attr('value',c).text(c+'日').appendTo($(this).next());
            }
            break;
        case '4':
        case '6':
        case '9':
        case '11':
            for(var c = 1;c<=30;c++){
                $('<option>').attr('value',c).text(c+'日').appendTo($(this).next());
            }
            break;
        case '2':
            for(var c = 1;c<=28;c++){
                $('<option>').attr('value',c).text(c+'日').appendTo($(this).next());
            }
            break;
    }
});

function update(o,type){
    var t;
    switch(type){
        case 1:
            t = 'in';
            break;
        case 2:
            t = 'out';
            break;
    }
    $('#signUpdate').fadeIn();
    $('#bodyCover').fadeIn();
    $('#signUpdate input[name=id]').val($(o).parent().parent().parent().attr('data-id'));
    $('#signUpdate input[name=type]').val(t);
    uo = o;
}

function updateTime(form){
    var data = $(form).serializeArray();
    var title ;
    switch (form.type.value){
        case 'in':
            title = '签到：';
            break;
        case 'out':
            title = '签退：';
            break;
    }
    $.ajax({
        url:'account/employeeDaySign.php',
        type:'PUT',
        data:data,
        success: function (data) {
            if(data.code==200){
                writeAlert('修改成功！');
                closeWindow();
                $(uo).prev().text(title+form.time.value);
            }
            else if(data.code==405){
                writeAlert('没有权限！');
                closeWindow();
            }else{
                writeAlert('修改失败！');
                closeWindow();
            }
        }
    });
    return false;
}

function getDSign(form){
    var data = $(form).serializeArray();
    $.ajax({
        url:'account/employeeDaySign.php',
        type:'GET',
        data:data,
        success: function (data) {
            if(data.code==201){
                writeAlert('没有数据！');
                clearRight();
                $('#dSignList').fadeIn();
                $('#dSignContainer').html("");
                $('#dSignDate').text(data.year+'年'+data.month+'月'+data.day+'日');
            }
            else if(data.code==200){
                clearRight();
                $('#dSignList').fadeIn();
                $('#dSignContainer').html('');
                $('#dSignDate').text(data.year+'年'+data.month+'月'+data.day+'日');
                data.signRecords.forEach(function (data) {
                    var sin = data.signIn==''?'无数据':data.signIn;
                    var sout = data.signOut==''?'无数据':data.signOut;
                    $('<div class="dSignBox">').attr('data-id',data.id)
                        .append($('<div class="dSignHeader">').text(data.name))
                        .append($('<div class="dSignInfo">')
                            .append($('<div class="dSignLine">')
                                .append($('<span class="dSignTitle">').text('签到：'+sin))
                                .append($('<span class="update" onclick="update(this,1)">').text('修改')))
                            .append($('<div class="dSignLine">')
                                .append($('<span class="dSignTitle">').text('签退：'+sout))
                                .append($('<span class="update" onclick="update(this,2)">').text('修改'))))
                        .appendTo($('#dSignContainer'));
                })
            }
        }
    });
    return false;
}