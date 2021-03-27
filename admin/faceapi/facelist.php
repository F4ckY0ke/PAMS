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
$groupres = $client->getGroupUsers($groupId);
$finaljson = array();
//print_r($res);
//$json = json_encode($groupres['result']);
//print_r($json);
for($i=0; $i<=count($groupres['result']['user_id_list'])-1; $i++){
	$userid=$groupres['result']['user_id_list'][$i];
	$userres = $client->faceGetlist($userid, $groupId);
	
	for($j=0; $j<=count($userres['result']['face_list'])-1; $j++){
//		$facename = array("facename"=>$userid);
		$facename = ["facename"=>$userid];
		$ares = $userres['result']['face_list'][$j]+$facename;
//		$finaljson = $finaljson + $ares;

		array_push($finaljson, $ares);
//		$arjson = json_encode($ares);
//		array_push($finaljson,$arjson);
//		print_r($ares);
//		echo $arjson;
	}
//	echo json_encode($finaljson);
}
//print_r($finaljson);
$finaljson =  json_encode($finaljson);
$res = "{\"Data\":{$finaljson}}";
echo $res;


?>