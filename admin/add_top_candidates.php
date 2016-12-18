<?php
	include '../connection/main_Connection.php';
	$sql_control = new sql_control();

	echo "Adding top ".$_POST['ranks'] . "<br/>";
	
	$ranking_details = explode("|", $_POST['ranks']);
	$ranks = $ranking_details[0];
	$event_id = $ranking_details[1];
	$round_id = $ranking_details[2];
	$gender = $ranking_details[3];
	
	//echo $gender;
	//var_dump($ranking_details);
	
	$result_top_candidates = $sql_control->get_top_candidates($round_id, $event_id, $ranks,$gender);
	
	while($row_top_candidates = mysql_fetch_array($result_top_candidates)){
		echo "<br/>".$row_top_candidates['candidate_id'] . " | " . $row_top_candidates['event_id'] . " | " . $row_top_candidates['round_id'] . " | " . $row_top_candidates['score']. "|" . $gender . "<br/>";
		$candidate_id = $row_top_candidates['candidate_id'];
		$round_id = $row_top_candidates['round_id'];
		$event_id = $row_top_candidates['event_id'];
		$is_round_qualifier = $sql_control->is_round_qualifier($candidate_id,$round_id);
		if($is_round_qualifier){
			echo "Already in the table </br>";
		}else{
			echo "Not yet in the table </br>";						
		}

		$is_round_qualifier_to_next = $sql_control->is_round_qualifier($candidate_id,$round_id+1);
															
		if($is_round_qualifier_to_next){
			echo "Already added to next round";
		}else{											//tried to added one to the round ID and thought that it would add it to the next round					
			$result_add_to_next_round = $sql_control->add_to_round($candidate_id,$round_id+1,$event_id);
			if($result_add_to_next_round){
				echo "SUCESSFULLY ADDED TO NEXT ROUND";
				header("location:view_scores.php");
			}else{
				echo "FAILED ADDING TO NEXT ROUND";
			}
		}
		
		
	}
	
?>