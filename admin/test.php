<!DOCTYPE html>
<html>
<head>
<title>basic example</title>
</head>
<body>
<script src="js/jquery.js"></script>
<script type="text/javascript" src="jquery-qrcode-master/src/jquery.qrcode.js"></script>
<script type="text/javascript" src="jquery-qrcode-master/src/qrcode.js"></script>
<div id="qrcodeCanvas"></div>
<script>
	jQuery('#qrcodeCanvas').qrcode({
		text	: "http://jeti1enne.com"
	});	
</script>

</body>
</html>