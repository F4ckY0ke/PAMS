<?php
$admin = false;
$repassword = $_POST["password"];
$reusername = $_POST["username"]; 
if ($reusername != null){
		$ini = parse_ini_file(".dbuser.ini");//读取配置文件
		// 创建连接
		$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
		// Check connection
		if ($conn->connect_error) {
		    die("连接失败: " . $conn->connect_error);
		} 
		
		
		$sql = "SELECT USER,PASSWORD FROM U_ADMIN where USER='$reusername';";
		$result = $conn->query($sql);
		 
		if ($result->num_rows > 0) {
		    // 输出数据
		    $admin = array();
		    $pwd = array();
		    while($row = $result->fetch_assoc()) {
		        //echo "id: " . $row["id"]. " - Name: " . $row["img"]. " " . $row["name"]. "<br>";
		        array_push($admin, $row["USER"]);
		        array_push($pwd, $row["PASSWORD"]);
		        if(password_verify($repassword, $pwd[0])){
		        	session_start();
		        	$_SESSION["admin"] = true;
		        	$_SESSION["adminid"] = $admin[0];
		        }
		        else{
					echo "<script type=\"text/javascript\">
					confirm('账号或密码错误！');
					window.location.href = 'login.html';
					</script>";
		        }
		    }
		} else {
		       echo "<script type=\"text/javascript\">
		        confirm('登陆失败，请重试！');
		        window.location.href = 'login.html';
		        </script>";
		}
		$conn->close();
}
?>

<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$admin = $_SESSION["admin"];
	$adminid = $_SESSION["adminid"];
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = 'login.html';
	   </script>";
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站后台管理系统</title>
<link rel="shortcut icon" href="images/favicon.ico" />
<link href="css/css.css" type="text/css" rel="stylesheet" />
</head>
<!--框架样式-->
<frameset rows="95,*,30" cols="*" frameborder="no" border="0" framespacing="0" name="indexframe">
<!--top样式-->
	<frame src="top.php?id=<?php echo $_GET["id"]?>" name="topframe" scrolling="no" noresize id="topframe" title="topframe" />
<!--contact样式-->
	<frameset id="attachucp" framespacing="0" border="0" frameborder="no" cols="194,12,*" rows="*">
		<frame scrolling="auto" noresize="" frameborder="no" name="leftFrame" src="left.php"></frame>
		<frame id="leftbar" scrolling="no" noresize="" name="switchFrame" src="swich.html"></frame>
		<frame scrolling="auto" noresize="" border="0" name="mainFrame" src="main.php"></frame>
	</frameset>
<!--bottom样式-->
	<frame src="bottom.html" name="bottomFrame" scrolling="No" noresize="noresize" id="bottomFrame" title="bottomFrame" />
</frameset><noframes></noframes>
<!--不可以删除-->
</html>