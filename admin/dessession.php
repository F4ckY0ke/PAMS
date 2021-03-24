<?php
	session_start();
	session_destroy();
	echo "<script type=\"text/javascript\">
	confirm('注销成功！');
	parent.location.href = 'login.html';
	</script>";
?>