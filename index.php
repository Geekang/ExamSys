<?php
header("Content-Type: text/html;charset=utf-8");//定义页面编码为utf
require_once('./aes.php');
?>
<?php
if(isset($_COOKIE["id"]) && isset($_COOKIE["data"])) {
	$id = $_COOKIE["id"];
	$data = $_COOKIE["data"];
	$key = md5(md5($id).$id);
	$decrypt = encrypt_aes_decode(base64_decode($data), $key);
	/*判断答题时间是否超时*/
	if(preg_match("/\[Time\]\((\d*)\)/", $decrypt, $matches)){
		/*没有超时*/
		if(time() < $matches[1]){
			header("Location:./exam.php");
			exit();
		}else{
			setcookie("data",0,time()-3600);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>山东财经大学第一届商务知识竞赛</title>
<link rel="icon" type="image/x-icon" href="favicon.ico">
<style type="text/css">
input{
	transition:all 0.30s ease-in-out;
	-webkit-transition: all 0.30s ease-in-out;
	-moz-transition: all 0.30s ease-in-out;
	
	border:#35a5e5 1px solid;
	border-radius:3px;
	outline:none;
	font-size:18px;
}
input:focus{
	box-shadow:0 0 5px rgba(81, 203, 238, 1);
	-webkit-box-shadow:0 0 5px rgba(81, 203, 238, 1);
	-moz-box-shadow:0 0 5px rgba(81, 203, 238, 1);
}
a{
	text-decoration:none;
	background:rgba(81, 203, 238, 1);
	color:white;padding: 6px 20px 6px 20px;
	font-family:'微软雅黑';
	border-radius:3px;
	
	-webkit-transition:all linear 0.30s;
	-moz-transition:all linear 0.30s;
	transition:all linear 0.30s;
}
a:hover{background:rgba(39, 154, 187, 1);}

.dopost{
	height:42px;
	padding-left:10px;
	
	
	background:#32be77;
	  border: #32be77 1px solid;
	color:white;padding: 6px 20px 6px 20px;
	font-family:'微软雅黑';
	border-radius:3px;
	
	-webkit-transition:all linear 0.30s;
	-moz-transition:all linear 0.30s;
	transition:all linear 0.30s;
}
.dopost:hover{
	background:#df5a5a;
	border-color: #df5a5a;
}
</style>
</head>

<body>
<div style="text-align:center;clear:both;margin-top:50px;font-size:30px;font-family: '微软雅黑';">
<h2>山东财经大学第一届商务知识竞赛</h2>
</div>

<div style="height:50px;margin:40px auto 0;text-align:center">
<form action="exam.php" method="POST">
	<input type="text" name="number" placeholder="学号"  style="height:40px;padding-left:10px;"/>
	<input type="text" name="name" placeholder="姓名或昵称"  style="height:40px;padding-left:10px;"/>
	<input type="submit" value="立即开始" class="dopost"/>
</form>
</div>
<br />
<br />
<br />
<br />
<br />
<div style="text-align:center;clear:both;font-size:20px;font-family: '微软雅黑';">
<p>主办单位：<a href="http://www.iqingnian.com/" target="_blank">共青团山东财经大学委员会</a></p>
<p style="color:white;">主办单位：<a href="http://www3.sdufe.edu.cn/iet/" target="_blank">山东财经大学国际经贸学院</a></p>
</div>
</body>
</html>