<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
	if($mode < '7'){
		die("您没有权限访问！");
	}
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
	  echo "<script type=\"text/javascript\">
	   confirm('您还未登录！');
	   window.location.href = 'login.html';
	   </script>";
}
$ini = parse_ini_file(".dbuser.ini");//读取配置文件
		// 创建连接
		$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
		// Check connection
		if ($conn->connect_error) {
		    die("连接失败: " . $conn->connect_error);
		} 
		
		
		$sql = "SELECT CANSHUVALUE AS JIEGUO FROM A_CANSHU WHERE CANSHUNAME = 'APP_ID' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'API_KEY' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'SECRET_KEY' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'QRAPI_KEY' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'QRSECRET_KEY' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'ACCESS_TOKEN' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'TIME_OUT' UNION ALL
				SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'FACE_SCORE';";
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
		        window.location.href = 'login.html';
		        </script>";
		}
		$conn->close();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="images/main/favicon.ico" />

<!-- Bootstrap -->
<link rel="stylesheet" href="bootstrap/bootstrap.min.css">  
<script src="bootstrap/jquery.min.js"></script>
<script src="bootstrap/bootstrap.min.js"></script>

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
<table>
<tr>

		<td>人脸识别APP_ID：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[0];?>"></td>
		<td>人脸识别API_KEY：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[1];?>"></td>
</tr>
<tr>

		<td>人脸识别SECRET_KEY：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[2];?>"></td>
		<td>二维码识别API_KEY：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[3];?>"></td>
</tr>
<tr>
		<td>二维码识别SECRET_KEY：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[4];?>"></td>
		<td>二维码识别ACCESS_TOKEN：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[5];?>"></td>
</tr>
<tr>
		<td>人脸识别等待时间：秒</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[6];?>"></td>
		<td>人脸识别分数阈值：</td>
		<td><input type="text" class="form-control mb-2" style="width: 300px" id="inlineFormInput" value="<?php echo $jieguo[7];?>"></td>
</tr>


</table>

</body>
</html>