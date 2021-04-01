<?php
session_start();
	if (isset($_REQUEST['authcode'])) {
		if (strtolower($_REQUEST['authcode'])==$_SESSION['authcode']) {//判断验证码是否正确
			$idcard = $_POST["idcard"];
			if(isCreditNo($idcard)){//调用函数验证身份证号是否正确
				$tel = $_POST["tel"];
				if (strlen($tel) == "11") {
					//判断手机号长度是不是11位
					$n = preg_match_all("/^1[3456789]\d{9}$/", $tel, $array);
					if($n){//正则匹配手机号是否正确			
						$ini = parse_ini_file("admin/.dbuser.ini");
						$conn = new mysqli($ini["dbservername"], $ini["dbusername"], $ini["dbpassword"], $ini["dbname"]);
								// Check connection
								if ($conn->connect_error) {
								    die("连接失败: " . $conn->connect_error);
								} 
								try {
									$sql = "CALL INSERTRANDOM('{$idcard}','{$tel}');";		
									$result = $conn->query($sql);
									if ($result->num_rows > 0) {
										$CJ_RANDOM = array();
										while($row = $result->fetch_assoc()) {
											array_push($CJ_RANDOM, $row["J_RANDOM"]);
											$QRcode = $CJ_RANDOM[0];
										}
										
									}
								}catch ( Exception $e ) {
						 			echo $e;
								}
								$conn->close();
					}
					else{
						echo "<script type=\"text/javascript\">
						confirm('请输入正确的手机号码！');
						window.location.href = 'index.php';
						</script>";
					}
				} else {
				  echo "<script type=\"text/javascript\">
				confirm('请输入11位的手机号码！');
				window.location.href = 'index.php';
				</script>";
				}
			}
			else{
				echo "<script type=\"text/javascript\">
				confirm('请输入正确的身份证号！');
				window.location.href = 'index.php';
				</script>";
			}

		}else{
			echo "<script type=\"text/javascript\">
			confirm('验证码错误！');
			window.location.href = 'index.php';
			</script>";
		}
	}

function isCreditNo($vStr){
 $vCity = array(
  '11','12','13','14','15','21','22',
  '23','31','32','33','34','35','36',
  '37','41','42','43','44','45','46',
  '50','51','52','53','54','61','62',
  '63','64','65','71','81','82','91'
 );
 if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
 if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
 $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
 $vLength = strlen($vStr);
 if ($vLength == 18) {
  $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
 } else {
  $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
 }
 if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
 if ($vLength == 18) {
  $vSum = 0;
  for ($i = 17 ; $i >= 0 ; $i--) {
   $vSubStr = substr($vStr, 17 - $i, 1);
   $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
  }
  if($vSum % 11 != 1) return false;
 }
 return true;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>二维码认证</title>
	<meta charset="utf-8">    
	<link rel="shortcut icon" href="images/favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="admin/js/jquery.js"></script>
	<script type="text/javascript" src="admin/jquery-qrcode-master/src/jquery.qrcode.js"></script>
	<script type="text/javascript" src="admin/jquery-qrcode-master/src/qrcode.js"></script>

	<script src="js/main.js"></script>

</head>
<body>




    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
                    <span class="login100-form-title-1">二维码认证扫码进入</span>
                </div>
				<div id="qrcodeCanvas" style="text-align: center;margin: 20px;"></div>
                <form class="login100-form validate-form" method="get" action="./index.php" style="padding-top: 10px;">
                    <div class="container-login100-form-btn" style="text-align:center;display:inline">
                        <button class="login100-form-btn" style="display:inline">再次登记</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
	jQuery('#qrcodeCanvas').qrcode({
		text	: "<?php echo $QRcode?>"
	});	
</script>




</body>
<style>
.copyrights{text-indent:-9999px;height:0;line-height:0;font-size:0;overflow:hidden;}
</style>

</html>