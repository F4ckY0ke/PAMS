<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
	$adminid = $_SESSION["adminid"];
	$mode = $_SESSION["mode"];
	if($mode < '4'){
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传人脸</title>

<link href="../css/css.css" type="text/css" rel="stylesheet" />
<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/main/favicon.ico" />

<!-- Bootstrap -->
<link rel="stylesheet" href="../bootstrap/bootstrap.min.css">  
<script src="../bootstrap/jquery.min.js"></script>
<script src="../bootstrap/bootstrap.min.js"></script>
 
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../DataTables/js/jquery.js"></script>

<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}

    #box{
      width: 454px;
      height: 340px;
      border: 2px solid #858585;
    }
    #imgshow{
      width: 100%;
      height: 100%;
    }
    #pox{
      width: 70px;
      height: 24px;
      overflow: hidden;
    }
  </style>

</head>


<body style="text-align:center">
<form action="pushaddface.php" method="post">
	<input type="hidden"  id="facebase" name="facebase">
	<div id="box" style="margin-left:33%">
		<img id="imgshow" src="page.png" alt=""/>
	</div>


			<div class="col-lg-6">
				<div class="input-group">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button">
							人脸名称
						</button>
					</span>
					<input type="text" class="form-control" name="facename">
				</div><!-- /input-group -->
	 		</div>

	<div id="pox">
		<input id="filed" type="file" accept="image/*"/>
	</div>
	<input type="submit" class="btn btn-primary" value="提交"/>
</form>


</body>

<script>
    //在input file内容改变的时候触发事件
    $('#filed').change(function(){
    //获取input file的files文件数组;
    //$('#filed')获取的是jQuery对象，.get(0)转为原生对象;
    //这边默认只能选一个，但是存放形式仍然是数组，所以取第一个元素使用[0];
      var file = $('#filed').get(0).files[0];
    //创建用来读取此文件的对象
      var reader = new FileReader();
    //使用该对象读取file文件
      reader.readAsDataURL(file);
    //读取文件成功后执行的方法函数
      reader.onload=function(e){
    //读取成功后返回的一个参数e，整个的一个进度事件
        console.log(e);
    //选择所要显示图片的img，要赋值给img的src就是e中target下result里面
    //的base64编码格式的地址
    document.getElementById("facebase").value = e.target.result;
        $('#imgshow').get(0).src = e.target.result;
      }
    })
</script>

</html>