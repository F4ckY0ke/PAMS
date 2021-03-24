
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台页面头部</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
</head>
<body onselectstart="return false" oncontextmenu=return(false) style="overflow-x:hidden;">
<!--禁止网页另存为-->
<noscript><iframe scr="*.htm"></iframe></noscript>
<!--禁止网页另存为-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="header">
  <tr>
    <td rowspan="2" align="left" valign="top" id="logo"><img src="images/main/logo.jpg" width="74" height="64"></td>
    <td align="left" valign="bottom">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="bottom" id="header-name">西船小区人员进出</td>
        <td align="right" valign="top" id="header-right">
        	<a href="dessession.php" target="topframe" onFocus="this.blur()" class="admin-out">注销</a>
            <a href="index.php" target="top" onFocus="this.blur()" class="admin-home">管理首页</a>
        	<a href="../index.php" target="_blank" onFocus="this.blur()" class="admin-index">网站首页</a>       	
            <span>
<!-- 日历 -->
<SCRIPT type=text/javascript src="js/clock.js"></SCRIPT>
<SCRIPT type=text/javascript>showcal();</SCRIPT>
            </span>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" valign="bottom">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top" id="header-admin">管理系统后台</td>
        <td align="left" valign="bottom" id="header-menu">
        <a href="index.php?id=1" target="_top" onFocus="this.blur()" <?php if ($_GET["id"] == '1' || $_GET["id"] == '') echo "id='menuon'"?>>后台首页</a>
        <a href="index.php?id=2" target="_top" onFocus="this.blur()" <?php if ($_GET["id"] == '2') echo "id='menuon'"?>>数据查询</a>
        <a href="index.php?id=3" target="_top" onFocus="this.blur()" <?php if ($_GET["id"] == '3') echo "id='menuon'"?>>人脸管理</a>
        <a href="index.php?id=4" target="_top" onFocus="this.blur()" <?php if ($_GET["id"] == '4') echo "id='menuon'"?>>联系我们</a>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>