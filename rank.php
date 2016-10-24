<?php
header("Content-Type: text/html;charset=utf-8");//定义页面编码为utf
require_once('./aes.php');
require_once('./cookies.php');
?>
<?php
//setcookie("finished",'1');
if(isset($_COOKIE["finished"]) && $_COOKIE["finished"] == '1'){
	if(time()>1427360400){
	setcookie("data",0,time()-3600);
	header("Location:./rank.php");
	exit();	
	}
	$decrypt;
	if(isset($_COOKIE["id"]) && isset($_COOKIE["data"])) {
		$id = $_COOKIE["id"];
		$data = $_COOKIE["data"];
		$key = md5(md5($id).$id);
		$decrypt = encrypt_aes_decode(base64_decode($data), $key);
	}
	
	$cookies = new cookies();

	$score = $cookies -> getScore($decrypt);
	
	
	if(isset($_COOKIE["last"]) && $_COOKIE["last"] == '1'){
		$score[0] = $score[0] + 0.5;
		setcookie("last",0,time()-3600);
	}
	
	$user = "\r\n".$cookies -> getNumber($decrypt).'|6d359f09d2d951df|'.$cookies -> getName($decrypt).'|6d359f09d2d951df|'.$score[0].'|6d359f09d2d951df|'.date('H:i:s', time());
	
	$open=fopen("./data/rank.txt","a" );
fwrite($open,$user);
fclose($open);
	
	//echo $user;
	//echo '<br />'.$cookies -> getName($decrypt);
	echo '恭喜你！提交成功！成绩将在17：00在此页面公布。<br />';
	setcookie("data",0,time()-3600);
	setcookie("finished",0,time()-3600);
}
$file_name = './data/rank.txt';
 $fp=fopen($file_name,'r');
 $i=0;
 $buffer2;
 while(!feof($fp))
 {
  $buffer2[$i]=fgets($fp);
  $i++;
 }
 fclose($fp);
 
 $result=array();
 $scoreList = array();
 $j = -1;
 for($i=0;$i<count($buffer2);$i++){
	$res = explode("|6d359f09d2d951df|",$buffer2[$i]);
	//echo '<br />学号：'.$res[0];
	//echo '<br />姓名或昵称：'.$res[1];
	//echo '<br />分数：'.$res[2];
	//echo '<br />完成时间：'.$res[3];
	
$j++;
$result=array_pad($result,$j,$res);
$scoreList=array_pad($scoreList,$i,$res[2]);

}
 //print_r($result);
 //print_r($scoreList);
 
?>