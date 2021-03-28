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

  <link rel="stylesheet" href="../layui/css/layui.css"  media="all">
   <script src="../layui/layui.js" charset="utf-8"></script>
   <script src="../js/jquery-3.3.1.min.js" charset="utf-8"></script>
   <link rel="stylesheet" href="../myicon/iconfont.css"  media="all">
   <style>
   body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;text-align:center}
        .layui-upload-drag-self {
            background-color: #fbfdff;
            border: 1px dashed #c0ccda;
            border-radius: 6px;
            box-sizing: border-box;
            width: 148px;
            height: 148px;
            line-height: 148px;
            vertical-align: top;
            display: inline-block;
            text-align: center;
            cursor: pointer;
            outline: 0;
            margin-right: 13px;
            float: none;
        }

        .layui-input-inlines-self {
            position: relative;
            margin-left: 0px;
            min-height: 36px;
            text-align: center;
        }

        .layui-upload-drag-self .layui-icon {
            font-size: 28px;
            color: #8c939d
        }

        .layui-upload-drag-self .img {
            position: relative;
            height: 148px;
            width: 148px;
        }

        .layui-upload-img {
            width: 148px;
            height: 148px;
            border-radius: 6px;
            margin-top: -3px;
            margin-left: -2px;
        }


        .handle {
            position: absolute;
            width: 148px;
            height: 100%;
            z-index: 100;
            border-radius: 6px;
            top: 0;
            background: rgba(59, 60, 61, 0.6);
            text-align: center;
        }

            .handle .icon-myself {
                z-index: 999;
                transition: all .3s;
                cursor: pointer;
                font-size: 25px;
                width: 25px;
                color: rgba(255, 255, 255, 0.91);
                margin: 0 4px;
            }
    </style>
 </head>
<body>
 <div class="layui-col-md12 layui-col-sm6 layui-col-xs12">
                    <div class="layui-card">
                        <div class="layui-card-body " style="background:#f2f0f5;">
                            <div class="layui-form layui-table-form layui-upload" style="text-align: center" action="" lay-filter="cfg-form" id="cfg-form">
                                <div class="layui-form-item" id="imgItem">
                                    
                                    <button type="button" id="importModel" class="layui-hide">图片导入</button>
                                    <div class="layui-input-inlines-self" id="imgItemInfo" >
                                        <div class="layui-upload-drag-self" id="importImg0" style="float:none">
                                            <div id="imgDivs0">
                                                <i class="layui-icon iconfont" id="uploadIcon0">+</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                			<div class="col-lg-6 text-center" style="width:1278.6px"> 
				<div class="input-group " style="width:280px;margin:0 auto;" id="facenamegroup">
					<span class="input-group-btn" >
						<button class="btn btn-default btn-lg" type="button">
							人脸名称
						</button>
					</span>
					<input type="text" class="form-control input-lg" name="facename" id="facename" style="width:100%">
				</div><!-- /input-group -->
				<br>
				<div>
				<input type="button" class="btn btn-primary btn-lg" value="提交" onClick="ss()">
				</div>
	 		</div>

  <script>
  function post(URL, PARAMS) {
  var temp = document.createElement("form");
  temp.action = URL;
  temp.method = "post";
  temp.style.display = "none";
  for (var x in PARAMS) {
    var opt = document.createElement("textarea");
    opt.name = x;
    opt.value = PARAMS[x];
    // alert(opt.name)
    temp.appendChild(opt);
  }
  document.body.appendChild(temp);
  temp.submit();
  return temp;
}


function ss(){
	var arr=[];
	var json = {};
	var a=	document.querySelectorAll(".layui-upload-img");

	for(let i in a){
//   	console.log(i);
//   	console.log(i.src);
          json[i]=a[i].src;
   	};
//   	cnosole.log()
//	console.log(arr);
//	for(var i=0;i<arr.length;i++){
//		console.log(a[i]);
//		json[i]=a[i];
//	};
	var jss = JSON.stringify(json);
	var utext = document.getElementById("facename");
	var text = utext.value;
	
 	post('pushaddface.php', {cm1:jss,cm2:text});

}

  
        layui.use('upload', function () {
        //layui.use(['div', 'layer', 'upload'], function () {
            var $ = layui.jquery,
                layer = layui.layer,
                form = layui.form,
                upload = layui.upload;

            //删除图片
            $(document).on('click', '[id^=delImg]', function () {
            	
                var importImgF = $('#imgItemInfo').find('div:first');//importImg0、importImg1、importImg2
                var empt = $(this).parent().parent().parent();//importImg0、importImg1、importImg2
                var nextImgSrc = $(this).parent().parent().parent().next().find('img').attr('src');//src
                //判断当前DIV后面的div的url是否为空
                if (!nextImgSrc) {
                    //判断是否为第一个
                    if (importImgF.attr('id') === empt.attr('id')) {
                        //-是 ，清空第一个 最后面的删除
                        //图片url清空
                        empt.find('img').attr('src', '');
                        $(this).parent().parent().addClass('layui-hide');
                        importImgF.find('i:first').removeClass('layui-hide');
                        count--;
                        $('#' + 'importImg' + count).remove();
                    } else {
                        // -否，删除当前
                        empt.remove();
                    }
                } else {
                    //如果有值删除当前div
                    empt.remove();
                }
                return false;
            });

            //图片预览
            $(document).on('click', '[id^=preImg]', function () {
                var iHtml = "<img src='" + $(this).parent().parent().find('img:first').attr('src') + "' style='width: 100%; height: 100%;'/>";
                layer.open({
                    type: 1,
                    shade: false,
                    title: false, //不显示标题
                    area: ['40%', '60%'],
                    content: iHtml //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
                });
                return false;
            });

            //图片绑定鼠标悬浮
            $(document).on("mouseenter", ".img", function () {
                //鼠标悬浮
                $(this).find('div:first').removeClass('layui-hide');
            }).on("mouseleave", ".img", function () {
                //鼠标离开
                $(this).find('div:first').addClass('layui-hide');
            });


            var imgsId,
                uploadDemoViewId,
                uploadIconId;

            $(document).on('click', '[id^=imgDivs]', function () {
                //给id赋值
                uploadIconId = $(this).find('i').attr('id');
                uploadDemoViewId = $(this).next().attr('id');
                imgsId = $(this).next().find('img').attr('id');
                $('#importModel').click();
            });
            var count = 1;
            upload.render({
                elem: '#importModel'
                , multiple: true
                , url: 'apiinfo.php' //改成您自己的上传接口

                , before: function (obj) {
                    
                    obj.preview(function(index, file, result){

 $('#imgItemInfo').append(
                            '<div class="layui-upload-drag-self" id="importImg' + count + '">' +
                            '<div id="imgDivs' + count + '">' +
                            '<i class="layui-icon layui-hide" id="uploadIcon' + count + '"> &#xe624; </i>' +
                            '</div>' +
                            '<div class="img" id="uploadDemoView' + count + '">' +
                            '<img class="layui-upload-img" id="imgs' + count + '" src="'+ result +'">' +
                            '<div class="handle layui-hide" id="handle' + count + '">' +
                            '<i class="layui-icon icon-myself iconfont" id="preImg' + count + '">&#xe633;</i>' +
                            '<i class="layui-icon icon-myself iconfont" id="delImg' + count + '">&#xe665;</i>' +
                            '</div>' + '</div>' + '</div>'
                        );
                        //console.log(result);
      });
                return false;
                }
                
            });
        });
    </script>

</body>
    </html>