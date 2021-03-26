<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>

<link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="bootstrap-3.3.7-dist/js/jquery-1.11.2.min.js"></script>
<script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

<style type="text/css">
#yl{ width:200px; height:200px; background-image:url(img/avatar.png); background-size:200px 200px;}
#file{ width:200px; height:200px; float:left; opacity:0;}
.modal-content{ width:500px;}
.kk{ margin-left:130px;}
</style>

</head>

<body>



<!-- 按钮触发模态框 -->
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    上传头像
</button>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    上传头像
                </h4>
            </div>
            <div class="modal-body">
                <form id="sc" action="uploads.php" method="post" enctype="multipart/form-data" target="shangchuan">
    
    <input type="hidden" name="tp" value="" id="tp" />
    
    <div id="yl" class="kk">
        <input type="file" name="file" id="file" onchange="document.getElementById('sc').submit()" />
    </div>
    
    
    
</form>

<iframe style="display:none" name="shangchuan" id="shangchuan">
</iframe>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <!--<button type="button" class="btn btn-primary">
                    提交更改
                </button>-->
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>


</body>

<script type="text/javascript">

//回调函数,调用该方法传一个文件路径，该变背景图
function showimg(url)
{
    var div = document.getElementById("yl");
    div.style.backgroundImage = "url("+url+")";
    
    document.getElementById("tp").value = url;
}

</script>

</html>