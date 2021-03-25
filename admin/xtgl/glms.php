<?php
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
	   window.location.href = '../login.html';
	   </script>";
}
$ini = parse_ini_file("../.dbuser.ini");//读取配置文件
		// 创建连接
		$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
		// Check connection
		if ($conn->connect_error) {
		    die("连接失败: " . $conn->connect_error);
		} 
		
		
		$sql = "SELECT CANSHUVALUE AS JIEGUO FROM A_CANSHU WHERE CANSHUNAME='GUANLIMOSHI';";
		$result = $conn->query($sql);
		 
		if ($result->num_rows > 0) {
		    // 输出数据
		    $jieguo = array();
		    while($row = $result->fetch_assoc()) {
		        //echo "id: " . $row["id"]. " - Name: " . $row["img"]. " " . $row["name"]. "<br>";
		        array_push($jieguo, $row["JIEGUO"]);
		    }
		} else {
		       echo "<script type=\"text/javascript\">
		        confirm('查询数据失败，请重试！');
		        window.location.href = '../main.php';
		        </script>";
		}
		$conn->close();
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
#main{ font-size:12px;}
#main span.time{ font-size:14px; color:#528dc5; width:100%; padding-bottom:10px; float:left}
#main div.top{ width:100%; background:url(images/main/main_r2_c2.jpg) no-repeat 0 10px; padding:0 0 0 15px; line-height:35px; float:left}
#main div.sec{ width:100%; background:url(images/main/main_r2_c2.jpg) no-repeat 0 15px; padding:0 0 0 15px; line-height:35px; float:left}
.left{ float:left}
#main div a{ float:left}
#main span.num{  font-size:30px; color:#538ec6; font-family:"Georgia","Tahoma","Arial";}
.left{ float:left}
div.main-tit{ font-size:14px; font-weight:bold; color:#4e4e4e; background:url(images/main/main_r4_c2.jpg) no-repeat 0 33px; width:100%; padding:30px 0 0 20px; float:left}
div.main-con{ width:100%; float:left; padding:10px 0 0 20px; line-height:36px;}
div.main-corpy{ font-size:14px; font-weight:bold; color:#4e4e4e; background:url(images/main/main_r6_c2.jpg) no-repeat 0 33px; width:100%; padding:30px 0 0 20px; float:left}
div.main-order{ line-height:30px; padding:10px 0 0 0;}
</style>
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="main">
  <tr>
    <td colspan="2" align="left" valign="top">
    <span class="time"><strong>您好！<?php echo $adminid ?></strong><u><?php if($mode == 7){echo "[超级管理员]";}else{echo "[管理员]";}; ?></u></span>
    
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" width="50%">
    <div class="main-tit">管理模式设置</div>
    <div class="main-con">
		<form name="glms" action="pushglms.php" method="get">
		<input type="radio" name="sex" value="1" <?php if ($jieguo[0] == 1){echo "checked='checked'";};?>>1.常住人员 + 外来访客</br>
		<input type="radio" name="sex" value="2" <?php if ($jieguo[0] == 2){echo "checked='checked'";};?>>2.仅常住人员</br>
		<input type="radio" name="sex" value="3" <?php if ($jieguo[0] == 3){echo "checked='checked'";};?>>3.仅外来访客</br>
		<input type="radio" name="sex" value="4" <?php if ($jieguo[0] == 4){echo "checked='checked'";};?>>4.任何人员</br>
		<input type="radio" name="sex" value="5" <?php if ($jieguo[0] == 5){echo "checked='checked'";};?>>5.小区封闭</br>
		</br>
		<input type="submit" class="btn btn-primary" value="提交">
		</form>
    </div>
    </td>
    
  </tr>
  
</table>
</body>
</html>