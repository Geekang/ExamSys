<?php
header("content-type:text/html;charset='utf-8'");
class cookies{

	/*获取学号*/
	function getNumber($decrypt){
		if(preg_match("/\[Number\]\((\d*)\)/", $decrypt, $matches)){
			return $matches[1];
		}else{
			setcookie("data",0,time()-3600);
		}
	}
	
	/*获取姓名或昵称*/
	function getName($decrypt){
		if(preg_match("/\[Name\]\((.*)\)\[Time\]/", $decrypt, $matches)){
			return $matches[1];
		}else{
			setcookie("data",0,time()-3600);
		}
	}
	
	/*获取下一题号*/
	function getNow($decrypt){
		if(preg_match("/\[Last\]\((\d*)\)/", $decrypt, $matches)){//获取Last
			$last = $matches[1];
			/*如果match[1]等于0，则取第一个*/
			if($last == '0'){
				preg_match('/\[Set\]\((\d+).*\)/', $decrypt, $matches);
			}else{
				if(preg_match('/\[Set\]\(.*,'.$last.',(\d+).*\)/', $decrypt, $matches)){
				}else{
					if(preg_match('/\[Set\]\('.$last.',(\d+).*\)/', $decrypt, $matches)){
					}else{
						preg_match('/\[Set\]\(.*,'.$last.'\)/', $decrypt, $matches);
						$matches[1] = '';
					}
				}
			}
			return $matches[1];
		}else{
			setcookie("data",0,time()-3600);
		}
	}

	/*设置上一题号*/
	function setLast($decrypt){
		$last = $this -> getNow($decrypt);

		$data = preg_replace("/\[Last\]\(\d*\)/","[Last](".$last.")",$decrypt);
		
		$duration = 5400;//考试时间
		$id = $_COOKIE["id"];
		$key = md5(md5($id).$id);
		$encrypt = base64_encode(encrypt_aes_encode($data, $key));
		setcookie("data",$encrypt);
		echo $data;
		
		return $data;
	}
	
	/*获取已答题数和当前分数*/
	function getScore($decrypt){
		if(preg_match("/\[Score\]\((\d+\.?\d?),(\d+)\)/", $decrypt, $matches)){
		}else{
			setcookie("data",0,time()-3600);
		}
		return array($matches[1],$matches[2]);
	}
	
	/*设置已答题数和当前分数*/
	function setScore($decrypt,$option){
	
		$id = $_COOKIE["id"];
		
		$lastScore = $this -> getScore($decrypt);//$lastScore代表上一题之前的分数
		
		if(preg_match("/\[Last\]\((\d+)\)/", $decrypt, $matches)){//获取Last
			$last = $matches[1];
			
			$answer = $option[0].$option[1].$option[2].$option[3].$option[4];
		
		setcookie("option",$answer);
		
		$check = $this -> checkAnswer($last,$answer);
		if($this -> checkAnswer($last,$answer)){
		//if(true){
			$mark = $this -> setContent($decrypt,$id);
		}else{
			$this -> setContent($decrypt,$id);
			$mark = 0;
		}
		
		}
		

		
		$data = preg_replace("/\[Score\]\((\d+\.?\d?),\d+\)/", "[Score](".($lastScore[0] + $mark).",".++$lastScore[1].")",$decrypt);
		
		$key = md5(md5($id).$id);
		$encrypt = base64_encode(encrypt_aes_encode($data, $key));
		setcookie("data",$encrypt);
		
		return $data;
	}
	
	function getLast($decrypt){
				if(preg_match("/\[Last\]\((\d+)\)/", $decrypt, $matches)){//获取Last
			$last = $matches[1];
		}
		return $last;
	}
	
	/*设置cookies，content*/
	function setContent($decrypt,$id){
	
		$now = $this -> getNow($decrypt);
		
		$mark = 0;
		if($now <=35){
			$mark = 1;
			$file_name = './data/single/'.$now.'.txt';
			$fp=fopen($file_name,'r');
			$i=0;
			$buffer2;
			
			while(!feof($fp)){
				$buffer2[$i]=fgets($fp);
				$i++;
			}
			
			fclose($fp);
			$content = '';
			
			for($i=0;$i<count($buffer2);$i++){
				switch($i){
					case 0:
						$content = $content.'<h4><span>(单选题)'.$now.'.</span>'.$buffer2[0].'</h4>';
						break;
					case 1:
						$content = $content.'<p><input type="radio" name="option" value="A" inputType="one"> <span>A.<span>'.$buffer2[1].'</span></span></p>';
						break;
					case 2:
						$content = $content.'<p><input type="radio" name="option" value="B" inputType="one"> <span>B.<span>'.$buffer2[2].'</span></span></p>';
						break;
					case 3:
						$content = $content.'<p><input type="radio" name="option" value="C" inputType="one"> <span>C.<span>'.$buffer2[3].'</span></span></p>';
						break;
					case 4:
						$content = $content.'<p><input type="radio" name="option" value="D" inputType="one"> <span>D.<span>'.$buffer2[4].'</span></span></p>';
						break;
				}
			}
		}else if($now > 35 & $now <= 50){
			$mark = 2;
			$file_name = './data/multi/'.$now.'.txt';
			$fp=fopen($file_name,'r');
			$i=0;
			$buffer2;
			while(!feof($fp)){
				$buffer2[$i]=fgets($fp);
				$i++;
			}
			fclose($fp);
			$content = '';
			for($i=0;$i<count($buffer2);$i++){
				switch($i){
					case 0:
						$content = $content.'<h4><span>(多选题)'.$now.'.</span>'.$buffer2[0].'</h4>';
						break;
					case 1:
						$content = $content.'<p><input type="checkbox" name="option[]" value="A" inputType="one"> <span>A.<span>'.$buffer2[1].'</span></span></p>';
						break;
					case 2:
						$content = $content.'<p><input type="checkbox" name="option[]" value="B" inputType="one"> <span>B.<span>'.$buffer2[2].'</span></span></p>';
						break;
					case 3:
						$content = $content.'<p><input type="checkbox" name="option[]" value="C" inputType="one"> <span>C.<span>'.$buffer2[3].'</span></span></p>';
						break;
					case 4:
						$content = $content.'<p><input type="checkbox" name="option[]" value="D" inputType="one"> <span>D.<span>'.$buffer2[4].'</span></span></p>';
						break;
					case 5:
						$content = $content.'<p><input type="checkbox" name="option[]" value="E" inputType="one"> <span>E.<span>'.$buffer2[5].'</span></span></p>';
						break;
				}
			}
		}else if($now > 50 & $now <= 80){
			$mark = 0.5;
			$file_name = './data/judge/'.$now.'.txt';
			$fp=fopen($file_name,'r');
			$i=0;
			$buffer2;
			while(!feof($fp)){
				$buffer2[$i]=fgets($fp);
				$i++;
			}
			fclose($fp);
			$content = $content.'<h4><span>(判断题)'.$now.'.</span>'.$buffer2[0].'</h4>';
			$content = $content.'<p><input type="radio" name="option[]" value="1" inputType="one"> <span>正确.<span></span></span></p>';
			$content = $content.'<p><input type="radio" name="option[]" value="2" inputType="one"> <span>错误.<span></span></span></p>';

		}
		
		$key = md5(md5($id).$id);
		$encrypt = base64_encode(encrypt_aes_encode($content, $key));
		setcookie("content",$encrypt);
		
		switch($now){
			case 36:
				$mark = 1;
				break;
			case 51:
				$mark = 2;
				break;
		}
		return $mark;
	}
	
	/*验证答案*/
	function checkAnswer($last,$answer){
		$file_name = './data/answer.php';
 $fp=fopen($file_name,'r');
 $i=0;
 $buffer2;
 while(!feof($fp))
 {
  $buffer2[$i]=fgets($fp);
  $i++;
 }
 fclose($fp);
 $content = '';

 $content=str_replace("\r\n","",$buffer2[$last]); 
 if($content == $answer){
	return true;
 }else{
	return false;
	}
		
	}
	
	

		
}
?>