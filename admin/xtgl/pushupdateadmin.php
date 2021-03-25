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
$user = $_POST["USER"];
$pwd = $_POST["PASSWORD"];
if ($_POST["SPASSWORD"] == $_POST["PASSWORD"] && !empty($user) && !empty($pwd)){
	$ini = parse_ini_file("../.dbuser.ini");//读取配置文件
	// 创建连接
	$con = mysqli_connect($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
	// Check connection
	if (mysqli_connect_errno($con))
	{
	    echo "连接 MySQL 失败: " . mysqli_connect_error();
	}
	$options = [
    'cost' => 10,
	];
	$hashpassword = password_hash($_POST["PASSWORD"], PASSWORD_BCRYPT, $options);
	$sql = "UPDATE U_ADMIN SET PASSWORD='{$hashpassword}',MODE={$_POST["MODE"]},TEL='{$_POST["TEL"]}',NAME='{$_POST["NAME"]}',IDCARD='{$_POST["IDCARD"]}' WHERE USER='{$_POST["USER"]}';";
	// 执行查询并输出受影响的行数
	mysqli_query($con,$sql);
	if (mysqli_affected_rows($con)==1){
		mysqli_close($con);
	echo "<script type=\"text/javascript\">
			alert('修改成功');
			window.location.href = 'main_list.php';
		   </script>";
	}
}
else{
	echo "<script type=\"text/javascript\">
	   confirm('失败！用户名密码为空或两次输入密码不一致。');
	   window.location.href = 'updateadmin.php?id={$_POST["USER"]}';
	   </script>";
}

?>