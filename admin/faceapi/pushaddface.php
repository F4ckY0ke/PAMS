<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
	if($mode == '0' || $mode == '2' || $mode == '4' || $mode == '6'){
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
$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 


$sql = "SELECT CANSHUVALUE AS JIEGUO FROM A_CANSHU WHERE CANSHUNAME = 'APP_ID' UNION ALL
		SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'API_KEY' UNION ALL
		SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'SECRET_KEY' UNION ALL
		SELECT CANSHUVALUE FROM A_CANSHU WHERE CANSHUNAME = 'GROUPID';";
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

require_once 'aip-php-sdk/AipFace.php';

//echo $jieguo[0];

// 你的 APPID AK SK
define("APP_ID", "$jieguo[0]");
define("API_KEY", "$jieguo[1]");
define("SECRET_KEY", "$jieguo[2]");

$client = new AipFace(APP_ID, API_KEY, SECRET_KEY);

$groupId = "$jieguo[3]";

$imageType = "BASE64";

$options = array();
$options["liveness_control"] = "NORMAL";

$json = $_POST["cm1"];
$userId = $_POST["cm2"];
if (!empty($json) && !empty($userId)){
	$sz=json_decode($_POST["cm1"],true);
	$length=count($sz);
	for($i=0;$i<$length;$i++){
		$image = $sz[$i];
		$image = substr($image,23);
		$res = $client->addUser($image, $imageType, $groupId, $userId, $options);
		if ($res['error_msg'] == 'SUCCESS'){
			$j = $i + 1;
			echo "<script type=\"text/javascript\">
				confirm('第{$j}张人脸添加成功！');
				</script>";
		}
		else{
			$j = $i + 1;
			echo "<script type=\"text/javascript\">
				confirm('第{$j}张人脸添加失败！错误:{$res['error_msg']}');
				</script>";
		}
	};     
	
	echo "<script type=\"text/javascript\">window.location.href = 'addface.php';</script>";
}
else{
	echo "<script type=\"text/javascript\">
		confirm('人脸名称不能为空！');
		window.location.href = 'addface.php';
		</script>";
}






	
	
?>