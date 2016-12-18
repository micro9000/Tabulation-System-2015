<?php

	//for male candidates
	if(isset($_POST['btn_view_scores'])){
			//$round_details = explode('|',$_POST['results']);
			$round_details = explode("|", $_POST['results']);
			$round_id = $round_details[0];
			$round_name = $round_details[1];
			$round_no = $round_details[2];
			$scoring_type = $round_details[3];
			
			//var_dump($round_details);

			//get the candidates
			$result_candidates = $sql_control->get_male_candidates_info($_SESSION['event_id']);

			//get categories
			$result_categories = $sql_control->get_categories($_SESSION['event_id'],$round_id);
			$temp_categories = "";
			while($row_categories = mysql_fetch_array($result_categories)){
				$temp_categories .= "<th>" . $row_categories['category_name'] . "</th>";
			}
			
			echo "<h2 align='center'>Current Round: $round_name</h2>"; 
			
			//create an array that will contain the details of candidate, and will be the basis for sorting them
			//$candidate_details[$id][$score]
			$candidate_details = array();
			$category_scores = array();
			echo "<h3 align='center'>Overall Scores : Male Candidates</h3>";
			//enumerate them								//print categories
			echo "<table align='center' border='1'><tr align='center'><th>Male Candidates</th>$temp_categories<th>$scoring_type</th></tr>";
			
			while($row_candidates = mysql_fetch_array($result_candidates)){
				$is_round_qualifier = $sql_control->is_round_qualifier($row_candidates['id'],$round_id);
				//echo "AFTER ISROUND QUALIFIER<BR/>";
				if($is_round_qualifier){
					//echo "INSIDE QUESTION ISROUND QUALIFIER";
					//echo "round qualifier";
					if($sql_control->query("SELECT COUNT(*) FROM category_score WHERE candidate_id = " . $row_candidates['id']) > 0){
						//echo $row_candidates['fname'] . " exists in the table<br/>";
						echo "<tr align='center'>";
						echo "<td>".$row_candidates['fname']." ". $row_candidates['lname'] ."</td>";//candidate name
	
						
						
						//counts the number of category						//for temporary the round id will be 1
						$category_count = $sql_control->get_category_count($_SESSION['event_id'],$round_id);
						
						//get the average for every categories
						$result_categories = $sql_control->get_categories($_SESSION['event_id'],$round_id);
						
						$total_of_average = 0;
						while($row_categories = mysql_fetch_array($result_categories)){
							
							$category_id = $row_categories['id'];
							$candidate_id = $row_candidates['id'];
							$event_id = $_SESSION['event_id'];
							
							//echo "category id = $category_id | candidate_id = $candidate_id | event_id = $event_id | round_id = $round_id <br/>";
							$scoring_details = $sql_control -> get_scoring_details($category_id, $candidate_id, $event_id, $round_id);
							$average_score = $scoring_details['average_score'];
							$max_score = $scoring_details['max_score'];
							//place the category scores in the array
							$category_scores[$category_id][$candidate_id]= $average_score;
							
							$judges_who_scored = $scoring_details['judge_scored_cnt'];
							$judges_cnt = $scoring_details['judges_cnt'];
							//echo "<td><b>$average_score/$max_score</b> ($judges_who_scored/$judges_cnt)</td>";
							printf("<td><b>%.2f/%d</b> (%d/%d)</td>",$average_score,$max_score,$judges_who_scored,$judges_cnt);
							$total_of_average += $average_score;
	
						}
						
						//echo "total of average $total_of_average";
						
						
						if($scoring_type=='Average'){
							//echo "<td><b>".$total_of_average/$category_count."</b></td>";
							printf("<td><b>%.2f</b></td>",$total_of_average/$category_count);
							$score = $total_of_average/$category_count;
							//echo $score."<br/>";
							//echo $row_candidates['id'];
							$candidate_details[$row_candidates['id']]=$score;
						}elseif($scoring_type=='Total'){
							//echo "<td><b>$total_of_average</b></td>";
							printf("<td><b>%.2f</b></td>",$total_of_average);
							$score = $total_of_average;
							$candidate_details[$row_candidates['id']]=$score;
							
						}
						echo "</tr>";
					}
				}

				
			}

			
			echo "</table>";
			echo "<br/><br/>";
			//var_dump($candidate_details) . "<br/>";
			
			//computes the overall score
			echo "<h3 align='center'>Overall Ranking</h3>";
			arsort($candidate_details);
			echo "<table align='center' border='1'><tr><th>Rank</th><th>Name</th><th>Overall Score</th></tr>";
			$rank=1;
			//create a str for storing ranks
			$ranks = "<select name='ranks'>";
			
			foreach ($candidate_details as $id => $candidate_score) {
				/*echo "Candidate_ID=" . $id . ", Score=" . $candidate_score;
     			echo "<br>";
				*/
				$row_candidate = $sql_control->get_candidate_info($id);
				if($rank==1){
					$color='brown';
				}else{
					$color='black';
				}
				echo "<tr align='center' style='color:$color'><td>$rank</td><td>" . $row_candidate['fname'] . " " . $row_candidate['lname'] . "</td>";
				printf("<td>%.2f</td><tr>",$candidate_score); 
				
				//add to overall score
				echo $sql_control->add_to_overallscore($id, $_SESSION['event_id'], $round_id,$candidate_score);
				//collect the candidate ranks 
											//$ranks|$event_id|$round_id
				$ranks .= "<option>$rank|". $_SESSION['event_id'] ."|$round_id|Male</option>";
				//create a combo box for generating top 5
				
				$rank++;
			}
			if(!($scoring_type=='Total')){
				$ranks.="</select>";
				//display the ui for adding top candidats
				echo "<form action='add_top_candidates.php' method='post'>";
				echo "<tr><td colspan='3'>Add top candidates: $ranks <input type='submit' value='Ok'/></td></tr>";
				echo "</form>";
			}
			
			echo "</table>";
			echo "<br/>";
			echo "<h3 align='center' >Per-Category Rankings</h3>";
			//segments the scores per category
			//$category_scores[$category_id][$candidate_id] = $average_score;
			foreach ($category_scores as $category_id => $candidate) {
				
				//echo $category_id . "<br/>";
				//segments the scores per category
				$scoring_details = $sql_control -> get_scoring_details($category_id, $candidate_id, $event_id, $round_id);
				//get the category info
				$category_row = $sql_control->get_category_info($category_id);
				$rank=1;
				
				echo "<table align='center' border='1'><tr><th>Rank</th><th colspan='2'>".$category_row['category_name']."</th></tr>";
				
				arsort($candidate);
				foreach ($candidate as $id => $score){
					if($rank==1){
						$color='brown';
					}else{
						$color='black';
					}
					$row_candidate = $sql_control->get_candidate_info($id);
					echo "<tr align='center' style='color:$color'><td>$rank</td><td>" . $row_candidate['fname'] . " " . $row_candidate['lname'] . "</td>" ;
					printf("<td>%.2f</td><tr>",$score);
					$rank++;
				}
				echo "</table>";
				echo "<br/>";
			}
			
	}

	echo "<hr/>";
	
	
?>
