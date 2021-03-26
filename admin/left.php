<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = 'login.html';
	   </script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧导航menu</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/sdmenu.js"></script>
<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};
	// ]]>
</script>
<style type=text/css>
html{ SCROLLBAR-FACE-COLOR: #538ec6; SCROLLBAR-HIGHLIGHT-COLOR: #dce5f0; SCROLLBAR-SHADOW-COLOR: #2c6daa; SCROLLBAR-3DLIGHT-COLOR: #dce5f0; SCROLLBAR-ARROW-COLOR: #2c6daa;  SCROLLBAR-TRACK-COLOR: #dce5f0;  SCROLLBAR-DARKSHADOW-COLOR: #dce5f0; overflow-x:hidden;}
body{overflow-x:hidden; background:url(images/main/leftbg.jpg) left top repeat-y #f2f0f5; width:194px;}
</style>
</head>
<body onselectstart="return false;" ondragstart="return false;" oncontextmenu="return false;">
<div id="left-top">
	<div><img src="images/main/member.gif" width="44" height="44" /></div>
    <span>用户：<?php echo $adminid ?><br>角色：<?php if($mode == 7){echo "[超级管理员]";}else{echo "[管理员]";}; ?></span>
</div>
    <div style="float: left" id="my_menu" class="sdmenu">
<?php 
if ($_GET["id"] == '1' || $_GET["id"] == ''){
	echo"<div>
			<span>系统管理</span>
			<a href='main.php' target='mainFrame' onFocus='this.blur()'>系统状态</a>
			<a href='xtgl/glms.php' target='mainFrame' onFocus='this.blur()'>管理模式</a>
			<a href='xtgl/cssz.php' target='mainFrame' onFocus='this.blur()'>系统参数设置</a>
			<a href='xtgl/main_list.php' target='mainFrame' onFocus='this.blur()'>管理员账号</a>
	      </div>";
	}
if ($_GET["id"] == '2'){
	echo"<div>
	        <span>数据查询</span>
			<a href='sjcx/localtable.php' target='mainFrame' onFocus='this.blur()'>常住人员信息表</a>
			<a href='sjcx/visitortable.php' target='mainFrame' onFocus='this.blur()'>临时访客信息表</a>
			<a href='sjcx/recordtable.php' target='mainFrame' onFocus='this.blur()'>人员进出记录</a>
	      </div>";
	}
if ($_GET["id"] == '3'){
	echo"<div>
	        <span>人脸管理</span>
	        <a href='faceapi/facetable.php' target='mainFrame' onFocus='this.blur()'>人脸库</a>
	        <a href='faceapi/addface.php' target='mainFrame' onFocus='this.blur()'>人脸注册</a>
	      </div>";
	}
if ($_GET["id"] == '4'){
	echo"<div>
	        <span>联系我们</span>
	        <a href='message.html' target='mainFrame' onFocus='this.blur()'>关于我们</a>
	        <a href='http://yoooke.top' target='_top' onFocus='this.blur()'>公司主页</a>
	      </div>";
	}





?>
    </div>
</body>
</html>