<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <title>临时访客信息登记</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
                    <span class="login100-form-title-1">临时访客信息登记</span>
                </div>

                <form class="login100-form validate-form" method="post" action="./QRcode.php">
                    <div class="wrap-input100 validate-input m-b-26" data-validate="身份证号不能为空">
                        <span class="label-input100">身份证号</span>
                        <input class="input100" type="number" name="idcard" oninput="if(value.length>18)value=value.slice(0,18)" placeholder="请输入身份证号">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-18" data-validate="手机号码不能为空">
                        <span class="label-input100">手机号码</span>
                        <input class="input100" type="number" name="tel" oninput="if(value.length>11)value=value.slice(0,11)" placeholder="请输入手机号码">
                        <span class="focus-input100"></span>
                    </div>
					<div class="wrap-input100 validate-input m-b-26" data-validate="验证码不能为空">
                        <span class="label-input100">验证码</span>
                        <input class="input100" type="number" name="authcode" placeholder="请输入验证码" oninput="if(value.length>4)value=value.slice(0,4)" style="width:40%;display:inline" >
						<a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./captcha.php?r='+Math.random() ">
						<img  id="captcha_img" border="1" src="./captcha.php?r=<?php echo rand(); ?>" alt="" width="120" height="40">
						</a>
                        <span class="focus-input100"></span>
                    </div>
                    

                    <div class="container-login100-form-btn" style="text-align:center;display:inline">
                        <button class="login100-form-btn" style="display:inline">登记信息</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/main.js"></script>
<style>
.copyrights{text-indent:-9999px;height:0;line-height:0;font-size:0;overflow:hidden;}
</style>

</body>

</html>