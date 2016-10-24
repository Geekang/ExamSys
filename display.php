<?php
class display{

	function question($decrypt){
		echo '<br />'.$decrypt;
		if(preg_match("/\[Last\]\((\d*)\)/", $decrypt, $matches)){
			/*如果match[1]等于0，则取第一个*/
			if($matches[1] == '0'){
				if(preg_match('/\[Set\]\((\d+).*\)/', $decrypt, $matches)){
					echo '第'.$matches[1].'题';
				}
			}else{
				if(preg_match('/\[Set\]\(.*,'.$matches[1].',(\d+).*\)/', $decrypt, $matches)){
					echo '第'.$matches[1].'题';
				}
			}
			return $matches[1];
		}
	}
	
	function getNext($decrypt){
		echo '<br />'.$decrypt;
		if(preg_match("/\[Last\]\((\d*)\)/", $decrypt, $matches)){
			/*如果match[1]等于0，则取第一个*/
			if($matches[1] == '0'){
				if(preg_match('/\[Set\]\((\d+).*\)/', $decrypt, $matches)){
				}
			}else{
				if(preg_match('/\[Set\]\(.*,'.$matches[1].',(\d+).*\)/', $decrypt, $matches)){
				}
			}
			return $matches[1];
		}else{
			setcookie("data",0,time()-3600);
		}
	}
	
}
?>