<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传人脸</title>

 
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="../DataTables/js/jquery.js"></script>

<style>
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

<body>
<body>
<form action="pushaddface.php" method="post">
	<input type="hidden"  id="facebase" name="facebase">
	<div id="box">
		<img id="imgshow" src="page.png" alt=""/>
	</div>
	<div id="pox">
		<input id="filed" type="file" accept="image/*"/>
	</div>
	<div>
		人脸名称：<input type="text" name="facename" />
	</div>
	<input type="submit" value="提交"/>
</form>
</body>

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