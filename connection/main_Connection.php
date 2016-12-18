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

			$_title = stripslashes($title);
			$_description = stripslashes($description);
			$_date = stripslashes($date);
			$_time = stripslashes($time);
			$_venue = stripslashes($venue);
			$_admin_id = stripslashes($admin_id);

			$title_ = mysql_real_escape_string($_title);
			$description_ = mysql_real_escape_string($_description);
			$date_ = mysql_real_escape_string($_date);
			$time_ = mysql_real_escape_string($_time);
			$venue_ = mysql_real_escape_string($_venue);
			$admin_id_ = mysql_real_escape_string($_admin_id);

			$sql = new sql_control();
			if($sql -> addEvent($title_, $description_, $date_, $time_, $venue_,$admin_id_)===true){
				return true;
			}
		}
		function add_round($round_no, $round_title, $event_id,$scoring_type){
			$sql = new sql_control();
			if($sql -> addRounds($round_no, $round_title, $event_id,$scoring_type) === true){
				return true;
			}
		}
	}//end class main

?>
