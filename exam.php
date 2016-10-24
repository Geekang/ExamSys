<?php
header("Content-Type: text/html;charset=utf-8");//定义页面编码为utf
require_once('./aes.php');
require_once('./display.php');
require_once('./cookies.php');
?>
<?php
if(time()<1427349600){
	setcookie("data",0,time()-3600);
	header("Location:./");
	exit();		
}
	if(time()>1427360400){
	setcookie("data",0,time()-3600);
	header("Location:./rank.php");
	exit();	}
$cookies = new cookies();
//判断data是否完全完整
if(isset($_COOKIE["id"]) && isset($_COOKIE["data"])) {
	$id = $_COOKIE["id"];
	$data = $_COOKIE["data"];
	$key = md5(md5($id).$id);
	$decrypt = encrypt_aes_decode(base64_decode($data), $key);
	/*判断答题时间是否超时*/
	if(preg_match("/\[Time\]\((\d*)\)/", $decrypt, $matches)){
		/*没有超时*/
		if(time() < $matches[1]){
			if(!preg_match("/\[Last\]\((\d*)\)/", $decrypt, $matches)){
				/*data不完整，清除并跳转*/
				setcookie("data",0,time()-3600);
				header("Location:./");
				exit();				
			}
		}else{
			header("Location:./");//超时跳转首页
			exit();
		}
	}else{
		/*data不完整，清除并跳转*/
		setcookie("data",0,time()-3600);
		header("Location:./");
		exit();
	}
	if(isset($_POST['next']) && isset($_POST['option'])){
		$decrypt = $cookies -> setLast($decrypt);
		if($cookies -> getNow($decrypt) == ''){
			$option = $_POST['option'];
			if($option[0] == 1){
				setcookie("last",'1');
			}else{
				setcookie("last",'0');
			}
			setcookie("finished",'1');
			header("Location:./rank.php");
			exit();
		}else{
			$cookies -> setScore($decrypt,$_POST['option']);
			header("Location:./exam.php");
			exit();
		}
	}
}else{
	if(isset($_POST['number']) && isset($_POST['name'])){
		$number = trim($_POST['number']);
		$name = trim($_POST['name']);
		//echo 'number:'.$number;
		if(empty($number) || empty($name)){
			header("Location:./");//参数为空，返回主页
			exit();
		}
		$id = md5(md5(microtime()).rand());
		setcookie("id",$id);
		
		$duration = 5400;//考试时间
		$data = '[Number]('.$_POST['number'].')[Name]('.$_POST['name'].')[Time]('.(time()+$duration).')[Last](0)[Set](1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80)[Score](0,0)';
		$key = md5(md5($id).$id);
		$encrypt = base64_encode(encrypt_aes_encode($data, $key));
		setcookie("data",$encrypt);
		
		$cookies -> setContent($data,$id);
		header("Location:./exam.php");
		exit();
	}else{
		/*cookies不完整(可能是直接访问)，跳转*/
		setcookie("data",0,time()-3600);
		header("Location:./");
		exit();
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
body {
  margin: 0px;
}
nav {
  margin-top: 50px;
  padding: 24px;
  text-align: center;
  font-family: Raleway;
  box-shadow: 2px 2px 8px -1px #000000;
}
#nav-1 {
  background: #3fa46a;
}

.link-1 {
  transition: 0.3s ease;
  background: #3fa46a;
  color: #ffffff;
  font-size: 20px;
  text-decoration: none;
  border-top: 4px solid #3fa46a;
  border-bottom: 4px solid #3fa46a;
  padding: 20px 0;
  margin: 0 20px;
}
.link-1:hover {
  border-top: 4px solid #ffffff;
  border-bottom: 4px solid #ffffff;
  padding: 6px 0; 
}
</style>
</head>

<body>
<?php
$id = $_COOKIE["id"];
$data = $_COOKIE["data"];
$key = md5(md5($id).$id);
$decrypt = encrypt_aes_decode(base64_decode($data), $key);

$now = $cookies -> getNow($decrypt);

$mark = 0;
if($now <=35){
	$mark = 1;
}else if($now > 35 & $now <= 50){
	$mark = 2;
}else if($now > 50 & $now <= 80){
	$mark = 0.5;
}else if($now > 80){
	$mark = 5;
}
echo '<!--';
echo $decrypt.'<br />';//cookies原文
echo '-->';
echo '您好，'.($cookies -> getName($decrypt)).'('.($cookies -> getNumber($decrypt)).')';

//$score = $cookies -> getScore($decrypt);
echo '<br />第'.$now.'小题，本题'.$mark.'分';

//$display = new display();
//data = $_COOKIE["data"];
?>
<form action="exam.php" method="POST">
<?php
echo encrypt_aes_decode(base64_decode( $_COOKIE["content"]), $key);
?>
	<input type="hidden" name="next" />
	<nav id="nav-1">
	<a class="link-1" href="javascript:sub();">下&nbsp;一&nbsp;题</a>
	</nav>
	<input id="next" type="submit" style="display:none;"/>
</form>


<script>
function sub(){
document.getElementById('next').click();
}
</script>
</body>
</html>
