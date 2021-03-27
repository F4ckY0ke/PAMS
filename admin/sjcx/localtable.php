<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
	if($mode == '0' || $mode == '1' || $mode == '4' || $mode == '5'){
		die("您没有权限访问！");
	}
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = '../login.html';
	   </script>";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="../css/css.css" type="text/css" rel="stylesheet" />
<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/main/favicon.ico" />

<!-- Bootstrap -->
<link rel="stylesheet" href="../bootstrap/bootstrap.min.css">  
<script src="../bootstrap/jquery.min.js"></script>
<script src="../bootstrap/bootstrap.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="../DataTables/css/jquery.dataTables.css">
 
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../DataTables/js/jquery.js"></script>
 
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="../DataTables/js/jquery.dataTables.js"></script>


<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#searchmain{ font-size:12px;}
#search{ font-size:12px; background:#548fc9; margin:10px 10px 0 0; display:inline; width:100%; color:#FFF; float:left}
#search form span{height:40px; line-height:40px; padding:0 0px 0 10px; float:left;}
#search form input.text-word{height:24px; line-height:24px; width:180px; margin:8px 0 6px 0; padding:0 0px 0 10px; float:left; border:1px solid #FFF;}
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(images/main/add.jpg) no-repeat -3px 7px #548fc9; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:right}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab{ border:1px solid #eaeaea; background:#FFF; font-size:12px;}
#main-tab th{ font-size:12px; background:url(images/main/list_bg.jpg) repeat-x; height:32px; line-height:32px;}
#main-tab td{ font-size:12px; line-height:40px;}
#main-tab td a{ font-size:12px; color:#548fc9;}
#main-tab td a:hover{color:#565656; text-decoration:underline;}
.bordertop{ border-top:1px solid #ebebeb}
.borderright{ border-right:1px solid #ebebeb}
.borderbottom{ border-bottom:1px solid #ebebeb}
.borderleft{ border-left:1px solid #ebebeb}
.gray{ color:#dbdbdb;}
td.fenye{ padding:10px 0 0 0; text-align:right;}
.bggray{ background:#f9f9f9}
</style>
</head>
<body>
<script>

$(document).ready(function() {//加载页面处理
	$('#babyTable').DataTable( {//发送请求把返回的json数据输出到表格babyTable
		"ajax": {
			url: "getlocal.php",
			dataSrc: 'Data'//指明数据来源
		},
		
		"columns": [//指定列数据
			{ "data": "ID" },
			{ "data": "USERFLAG" },
			{ "data": "FACENAME" },
			{ "data": "NAME" },
			{ "data": "IDCARD" },
			{ "data": "TEL" },
			{ "data": "ADDRESS" },
			{ "data": "REGTIME" },
			{//新建一列存放操作按钮
				className : "td-operation text-center",
				data: null,
				defaultContent:"",
				orderable : false,
				width : "100px"
			}
		],
		  "createdRow": function ( row, data, index) {
			//行渲染回调,在这里可以对该行dom元素进行任何操作
			var $btn = $('<div class="btn-group text-cen">'+
			    '<input type="button" class="btn btn-success" onclick="sendUpdate(\''+row.cells[0].innerHTML+'\')" value="修改">'+//获取当前行第3列内数据做拼接后生成按钮
			    '<input type="button" class="btn btn-danger" onclick="sendDel(\''+row.cells[0].innerHTML+'\')" value="删除">'+
			    '</div>'+
			    '</div>');
			$('td', row).eq(-1).append($btn);//在最后一列插入按钮数据
		}
		
	} );

});
$.fn.dataTable.defaults.oLanguage = {
    "sProcessing": "处理中...",
    "sLengthMenu": "显示 _MENU_ 项结果",
    "sZeroRecords": "没有匹配结果",
    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
    "sInfoPostFix": "",
    "sSearch": "搜索：",
    "sUrl": "",
    "sEmptyTable": "表中数据为空",
    "sLoadingRecords": "载入中...",
    "sInfoThousands": ",",
    "oPaginate": {
        "sFirst": "首页",
        "sPrevious": "上页",
        "sNext": "下页",
        "sLast": "末页"
    },
    "oAria": {
        "sSortAscending": ": 以升序排列此列",
        "sSortDescending": ": 以降序排列此列"
    }
};
</script>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            您的位置：常住人员管理
        </div>
    </div>
    <div class="panel-body">
        <table id="babyTable" class="table table-bordered table-striped table-hover">
            <thead>
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
        <th align="center" valign="middle" class="borderright">启用标识</th>
        <th align="center" valign="middle" class="borderright">人脸名称</th>
        <th align="center" valign="middle" class="borderright">姓名</th>
        <th align="center" valign="middle" class="borderright">身份证号</th>
        <th align="center" valign="middle" class="borderright">联系方式</th>
        <th align="center" valign="middle" class="borderright">常住地址</th>
        <th align="center" valign="middle" class="borderright">注册时间</th>
        <th align="center" valign="middle">操作</th>
      </tr>
        </thead>
        </table>
    </div>
</div>

<script>
function sendDel(id){
	var con;
	con = confirm("确认删除ID为"+id+"的常住人员么？");//弹出确认对话框
	if(con==true){//单击确认按钮布尔变量为true
		window.location.href = "dellocal.php?id="+id+"";
	}
	else window.location.href = "localtable.php";//单击取消按钮刷新页面

}

function sendUpdate(id){
	window.location.href = "updatelocal.php?id="+id+"";//点击修改按钮将按钮所在行的id以get参数发送给更新页面
}
</script>
</body>
</html>