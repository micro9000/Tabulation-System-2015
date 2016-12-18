<?php

	include 'mysql_control.php';	
	include 'adminSession.php';
	class main{


		function callingAll(){
			$sql = new sql_control();
		}

		function add_admin($user_name, $first_name, $second_name, $middle_name, $password){
			$sql = new sql_control();
			$sql -> addAdmin($user_name, $first_name, $second_name, $middle_name, $password);
		}
		function add_new_pageant($title, $description, $date, $time, $venue, $admin_id){
			//$admin_session = new adminSession();
			$sql = new sql_control();
			if($sql -> addEvent($title, $description, $date, $time, $venue,$admin_id)===true){
				return true;
			}
		}
		function add_round($round_no, $round_title, $event_id ,$round_percentage){
			$sql = new sql_control();
			if($sql -> addRounds($round_no, $round_title, $event_id, $round_percentage) === true){
				return true;
			}
		}
	}//end class main

?>
