<?php
$admin = false;
session_start();
	if (isset($_REQUEST['authcode'])) {
		if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {//判断验证码是否正确

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
					
					
					$sql = "SELECT USER,PASSWORD,MODE FROM U_ADMIN where USER='$reusername';";
					$result = $conn->query($sql);
					 
					if ($result->num_rows > 0) {
					    // 输出数据
					    $admin = array();
					    $pwd = array();
					    $mode = array();
					    while($row = $result->fetch_assoc()) {
					        //echo "id: " . $row["id"]. " - Name: " . $row["img"]. " " . $row["name"]. "<br>";
					        array_push($admin, $row["USER"]);
					        array_push($pwd, $row["PASSWORD"]);
					        array_push($mode, $row["MODE"]);
					        if(password_verify($repassword, $pwd[0])){
					        	session_start();
					        	$_SESSION["admin"] = true;
					        	$_SESSION["adminid"] = $admin[0];
					        	$_SESSION["mode"] = $mode[0];
					        }
					        else{
								echo "<script type=\"text/javascript\">
								confirm('账号或密码错误！');
								window.location.href = 'login.php';
								</script>";
					        }
					    }
					} else {
					       echo "<script type=\"text/javascript\">
					        confirm('登陆失败，请重试！');
					        window.location.href = 'login.php';
					        </script>";
					}
					$conn->close();
			}

		}else{
			echo "<script type=\"text/javascript\">
			confirm('验证码错误！');
			window.location.href = 'index.php';
			</script>";
		}
	}

if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$admin = $_SESSION["admin"];
	$adminid = $_SESSION["adminid"];
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = 'login.php';
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
		<frame scrolling="auto" noresize="" frameborder="no" name="leftFrame" src="left.php?id=<?php echo $_GET["id"]?>"></frame>
		<frame id="leftbar" scrolling="no" noresize="" name="switchFrame" src="swich.html"></frame>
		<frame scrolling="auto" noresize="" border="0" name="mainFrame" src="<?php 
if ($_GET["id"] == '1' || $_GET["id"] == ''){echo "main.php";}
if ($_GET["id"] == '2'){	echo "sjcx/localtable.php";}
if ($_GET["id"] == '3'){	echo "faceapi/facetable.php";}
if ($_GET["id"] == '4'){	echo "message.html";}
		?>"></frame>
	</frameset>
<!--bottom样式-->
	<frame src="bottom.html" name="bottomFrame" scrolling="No" noresize="noresize" id="bottomFrame" title="bottomFrame" />
</frameset><noframes></noframes>
<!--不可以删除-->
</html>