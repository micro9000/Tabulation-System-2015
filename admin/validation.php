<?php
	class validation_form{
		//check the date format for inserting date in table event_pageant
		function checkDate($_date){
			$minYear = 2014;
			$maxYear = 2016;
			$err_msg = '';
			$date_pattern = "/^(\d{4})\-(\d{1,2})\-(\d{1,2})$/";

			if($_date != ''){
				if(preg_match($date_pattern, $_date)){
					$ls = split('[-./]', $_date);
						if($ls[0] < $minYear && $ls[0] > $maxYear){
							$err_msg = "Year is between 2015 and 2016 only";
						}else if($ls[1] < 1 && $ls[1] > 31){
							$err_msg =  "Invalid Day Input";
						}else if($ls[2] < 1 && $ls[2] > 12){
							$err_msg = "Invalid Month input";
						}else{
							return true;
						}
				}else{
					$err_msg = "Invalid Date format".$_date;
				}
				if($err_msg != ''){
					echo $err_msg;
					echo "<br/>";
					return false;
				}
			}
			return true;
		}
		//check the input Time 
		function checkTime($_time){
			$time_pattern = "/^(\d{1,2}):(\d{2})(:00)?([ap]m)?$/";
			$err_time ='';
			if($_time != ''){
				if(preg_match($time_pattern, $_time)){
					$lst = split('[:]', $_time);
						if($lst[0] < 1 || $lst[0] > 23){
							$err_time = "Invalid Time Format :".$lst[0];
						}else if($lst[1] > 59){
							$err_time = "Invalid Time Format :".$lst[1];
						}else{
							return true;
						}		
				}else{
					$err_time = "Invalid Time Format :".$_time;
				}
				if($err_time != ''){
					echo $err_time;
					return false;
				}
			}
			return true;
		}
		function checkTextBox(){
				if(isset($_POST['btn_login'])){
					if(empty($_POST['user_name']) && empty($_POST['ad_password'])){
						echo "Username and Password is invalid";
					}
				}
		}

	}
	
?>