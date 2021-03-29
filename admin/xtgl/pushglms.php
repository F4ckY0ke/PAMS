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
	   window.location.href = '../login.php';
	   </script>";
}

$ini = parse_ini_file("../.dbuser.ini");//读取配置文件
// 创建连接
$con = mysqli_connect($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
// Check connection
if (mysqli_connect_errno($con))
{
    echo "连接 MySQL 失败: " . mysqli_connect_error();
}
$sql = "UPDATE A_CANSHU SET CANSHUVALUE='{$_GET["sex"]}' WHERE CANSHUNAME='GUANLIMOSHI';";
// 执行查询并输出受影响的行数
mysqli_query($con,$sql);
if (mysqli_affected_rows($con)==1){
	mysqli_close($con);
echo "<script type=\"text/javascript\">
	   confirm('设置成功！');
	   window.location.href = 'glms.php';
	   </script>";
}
?>
