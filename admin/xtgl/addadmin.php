<?php
//添加管理员页面
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
	if($mode < '4'){
		die("您没有权限访问！");
	}
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = '../login.php';
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

<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#searchmain{ font-size:12px;}
#search{ font-size:12px; background:#548fc9; margin:10px 10px 0 0; display:inline; width:100%; color:#FFF}
#search form span{height:40px; line-height:40px; padding:0 0px 0 10px; float:left;}
#search form input.text-word{height:24px; line-height:24px; width:180px; margin:8px 0 6px 0; padding:0 0px 0 10px; float:left; border:1px solid #FFF;}
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(images/main/add.jpg) no-repeat 0px 6px; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF}
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
.bggray{ background:#f9f9f9; font-size:14px; font-weight:bold; padding:10px 10px 10px 0; width:120px;}
.main-for{ padding:10px;}
.main-for input.text-word{ width:310px; height:36px; line-height:36px; border:#ebebeb 1px solid; background:#FFF; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; padding:0 10px;}
.main-for select{ width:310px; height:36px; line-height:36px; border:#ebebeb 1px solid; background:#FFF; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666;}
.main-for input.text-but{ width:100px; height:40px; line-height:30px; border: 1px solid #cdcdcd; background:#e6e6e6; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#969696; float:left; margin:0 10px 0 0; display:inline; cursor:pointer; font-size:14px; font-weight:bold;}
#addinfo a{ font-size:14px; font-weight:bold; background:url(images/main/addinfoblack.jpg) no-repeat 0 1px; padding:0px 0 0px 20px; line-height:45px;}
#addinfo a:hover{ background:url(images/main/addinfoblue.jpg) no-repeat 0 1px;}
</style>
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="searchmain">
  <tr>
    <td width="99%" align="left" valign="top">您的位置：管理员账号&nbsp;&nbsp;>&nbsp;&nbsp;新增管理员</td>
  </tr>
  <tr>
    <td align="left" valign="top" id="addinfo">
    <a href="addadmin.php" target="mainFrame" onFocus="this.blur()" class="add">新增管理员</a>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <form method="post" action="pushaddadmin.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">用户名：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="USER" value="" class="text-word">*
        </td>
        </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">用户密码：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="password" name="PASSWORD" value="" class="text-word">*
        </td>
        </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">确认密码：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="password" name="SPASSWORD" value="" class="text-word">*
        </td>
      </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">用户权限：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <select name="MODE" id="level">
	    <option value="1" >&nbsp;&nbsp;1.人脸管理员</option>
	    <option value="2" >&nbsp;&nbsp;2.数据管理员</option>
	    <option value="3" >&nbsp;&nbsp;3.人脸+数据管理员</option>
	    <option value="4" >&nbsp;&nbsp;4.系统管理员</option>
	    <option value="5" >&nbsp;&nbsp;5.系统+人脸管理员</option>
	    <option value="6" >&nbsp;&nbsp;6.系统+数据管理员</option>
	    <option value="6" >&nbsp;&nbsp;7.超级管理员</option>
        </select>*
        </td>
      </tr>
            <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">联系方式：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="TEL" value="" class="text-word">
        </td>
      </tr>

      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">姓名：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="NAME" value="" class="text-word">
        </td>
      </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">身份证号：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="IDCARD" value="" class="text-word">
        </td>
      </tr>

      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">&nbsp;</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input name="" type="submit" value="提交" class="btn btn-primary">
        <input name="" type="reset" value="重置" class="btn btn-default"></td>
        </tr>
    </table>
    </form>
    </td>
    </tr>
</table>
</body>
</html>