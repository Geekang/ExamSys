<?php
header("Content-Type: text/html;charset=utf-8");//定义页面编码为utf
?>
<!doctype html>
<html>
<head>
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.imagetable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #999999;
	border-collapse: collapse;
}
table.imagetable th {
	background:#b5cfd2 url('cell-blue.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
table.imagetable td {
	background:#dcddc0 url('cell-grey.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
.imagetable{
	margin:auto;	
}
</style>
</head>
<body>
<div style="text-align:center;clear:both;margin-top:50px;font-size:30px;font-family: '微软雅黑';">
<h2>山东财经大学第一届商务知识竞赛</h2>
<h3>成绩排名（前100名）</h3>
</div>

<!-- Table goes in the document BODY -->
<table class="imagetable">
<tr>
	<th>名次</th><th>学号</th><th>姓名</th><th>分数(满分80)</th>
</tr>



<?php
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
 $nameList = array();
 $numberList = array();
 $j = -1;
 for($i=0;$i<count($buffer2);$i++){
	$res = explode("|6d359f09d2d951df|",$buffer2[$i]);
	//echo '<br />学号：'.$res[0];
	//echo '<br />姓名或昵称：'.$res[1];
	//echo '<br />分数：'.$res[2];
	//echo '<br />完成时间：'.$res[3];
	
$j++;
$result=array_pad($result,$j,$res);
$numberList=array_pad($numberList,$i,$res[0]);
$nameList=array_pad($nameList,$i,$res[1]);
$scoreList=array_pad($scoreList,$i,$res[2]);
//$scoreList=array_pad($scoreList,$i,$res[2]);

}
array_multisort( $scoreList,$numberList,$nameList);
 //print_r($numberList);
 //print_r($scoreList);
 //print_r($nameList);
 //rsort($scoreList);
 //print_r($scoreList);
 
 $num = count($nameList);
 
 $j = 0;
for($i=0;$i<$num;++$i){
	if($j>99){
		break;
	}
	echo '<tr><td>'.($i+1).'</td><td>'.$numberList[$num - $i - 1].'</td><td>'.$nameList[$num - $i - 1].'</td><td>'.$scoreList[$num - $i - 1].'</td></tr>';
	//echo '第'.($i+1).'名:';
	//echo $numberList[$num - $i - 1].'|';
	//echo $nameList[$num - $i - 1].'|';
	//echo $scoreList[$num - $i - 1].'|';
	//echo '<br />';
	$j++;
}
?>

</table>
</body>
</html>