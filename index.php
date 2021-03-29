<?php 
$ini = parse_ini_file("admin/.dbuser.ini");//读取配置文件
		// 创建连接
		$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
		// Check connection
		if ($conn->connect_error) {
		    die("连接失败: " . $conn->connect_error);
		} 
		
		
		$sql = "SELECT CANSHUVALUE FROM A_CANSHU where CANSHUNAME='GUANLIMOSHI';";
		$result = $conn->query($sql);
		 
		if ($result->num_rows > 0) {
		    // 输出数据
		    $moshi = array();
		    while($row = $result->fetch_assoc()) {
		        //echo "id: " . $row["id"]. " - Name: " . $row["img"]. " " . $row["name"]. "<br>";
		        array_push($moshi, $row["CANSHUVALUE"]);
		        if($moshi[0] == '2' || $moshi[0] == '5'){
		        	die("不允许访客登记，请联系管理员！");
		        }
		    }
		} else {
		       echo "<script type=\"text/javascript\">
		        confirm('加载失败，请重试！');
		        window.location.href = 'login.html';
		        </script>";
		}
		$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"> 
<title>小区访客登记</title> 
<link rel="shortcut icon" href="favicon.ico" />

</head>
<body>
<h1>小区访客登记</h1>
		<form method="post" action="./QRcode.php">
 			身份证号：<input type="text" name="idcard" />
 			手机号码：<input type="text" name="tel" />
			<p>验证码：
				<input type="text" name="authcode" value="" />
				<a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./captcha.php?r='+Math.random() ">
				<img  id="captcha_img" border="1" src="./captcha.php?r=<?php echo rand(); ?>" alt="" width="100" height="30">
				</a>
			</p>
 
			<p>
				<input type="submit" value="提交" style="padding: 6px 20px;">
			</p>
 
		</form>
</body>
</html>
