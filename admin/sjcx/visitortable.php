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
			url: "getvisitor.php",
			dataSrc: 'Data'//指明数据来源
		},
		
		"columns": [//指定列数据
			{ "data": "ID" },
			{ "data": "CODEFLAG" },
			{ "data": "RANDOM" },
			{ "data": "IDCARD" },
			{ "data": "TEL" },
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

</script>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="searchmain">
  <tr>
    <td width="99%" align="left" valign="top">您的位置：临时访客管理</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="90%" align="left" valign="middle">

         </td>
  		  <td width="10%" align="center" valign="middle" style="text-align:right; width:150px;"></td>
  		</tr>

	</table>
    </td>
  </tr>

  <tr>
    <td align="left" valign="top">
    
    <table id="babyTable" width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
            <thead>
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
        <th align="center" valign="middle" class="borderright">启用标识</th>
        <th align="center" valign="middle" class="borderright">随机二维码</th>
        <th align="center" valign="middle" class="borderright">身份证号</th>
        <th align="center" valign="middle" class="borderright">联系方式</th>
        <th align="center" valign="middle" class="borderright">注册时间</th>
        <th align="center" valign="middle">操作</th>
      </tr>
        </thead>
    </table></td>
    </tr>

</table>
<script>
function sendDel(id){
	var con;
	con = confirm("确认删除ID为"+id+"的访客信息么？");//弹出确认对话框
	if(con==true){//单击确认按钮布尔变量为true
		window.location.href = "delvisitor.php?id="+id+"";
	}
	else window.location.href = "visitortable.php";//单击取消按钮刷新页面

}

function sendUpdate(id){
	window.location.href = "updatevisitor.php?id="+id+"";//点击修改按钮将按钮所在行的id以get参数发送给更新页面
}
</script>
</body>
</html>