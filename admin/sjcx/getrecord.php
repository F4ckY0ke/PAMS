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
	   window.location.href = '../login.html';
	   </script>";
}
header("content-Type: text/html; charset=utf-8");//字符编码设置 
$ini = parse_ini_file("../.dbuser.ini");//读取配置文件
		// 创建连接
		$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
		// Check connection
if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error); 
} 
  
$sql = "SELECT * FROM L_VISIT;"; 
$result = $conn->query($sql); 
  
$arr = array(); 
// 输出每行数据 
while($row = $result->fetch_assoc()) { 
  $count=count($row);//不能在循环语句中，由于每次删除row数组长度都减小 
  for($i=0;$i<$count;$i++){ 
    unset($row[$i]);//删除冗余数据 
  } 
  array_push($arr,$row); 
  
} 
//print_r($arr); 
$data = json_encode($arr,JSON_UNESCAPED_UNICODE);//json编码 
$res = "{\"Data\":{$data}}";
echo $res;

$conn->close();
?>