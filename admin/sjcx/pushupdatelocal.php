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
	   window.location.href = '../login.php';
	   </script>";
}
$facename = $_POST["FACENAME"];
if (!empty($facename)){
	$ini = parse_ini_file("../.dbuser.ini");//读取配置文件
	// 创建连接
	$con = mysqli_connect($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
	// Check connection
	if (mysqli_connect_errno($con))
	{
	    echo "连接 MySQL 失败: " . mysqli_connect_error();
	};

	$sql = "UPDATE U_LOCAL SET USERFLAG={$_POST["USERFLAG"]},FACENAME='{$_POST["FACENAME"]}',NAME='{$_POST["NAME"]}',IDCARD='{$_POST["IDCARD"]}',TEL='{$_POST["TEL"]}',ADDRESS='{$_POST["ADDRESS"]}',REGTIME=NOW() WHERE ID={$_GET["id"]};";
	// 执行查询并输出受影响的行数
	mysqli_query($con,$sql);
	if (mysqli_affected_rows($con)==1){
		mysqli_close($con);
	echo "<script type=\"text/javascript\">
			alert('更新成功');
			window.location.href = 'localtable.php';
		   </script>";
	};
}
else{
	echo "<script type=\"text/javascript\">
	   confirm('失败！人脸名称必须输入。');
	   window.location.href = 'updatelocal.php?id={$_GET["id"]}';
	   </script>";
}

?>