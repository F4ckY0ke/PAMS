<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"> 
<title>小区访客登记</title> 

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
