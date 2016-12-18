<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
    if(!isset($_SESSION['event_id']))
        header('location:adminForm.php');
	/*if(!isset($_SESSION['event_id'],$_SESSION['round_id'])){
		header("location:adminForm.php");
	}*/
	$id_admin = $_SESSION['admin_id'];
	echo "Admin ID: $id_admin | ";
    echo "Event ID: " . $_SESSION['event_id'];
	/*
    if(isset($_POST['round_details'])){
        $round_arr = explode("|", $_POST['round_details'],-1);
        $_SESSION['round_id'] = $round_arr[0];
    }
	$sql = new sql_control();
    echo "Round ID: " . $_SESSION['round_id'];
	 * 
	 */
	 $sql = new sql_control();
?>
<html>
	<head>
		<title>Add Candidate to Round</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>
	<body>

		<table>
			<tr>
				<td><h3><a href='adminForm.php'>Home</a> | </h3></td>
                <td><h3><a href='main_pageant_form.php'>Back</a>|</h3></td>
                <td><h3><a href='logout.php'>Logout</a></h3></td>
			</tr>
		</table>
        

        
        <form action="add_candidate_to_round.php" method="post">

            <table border='1'>
                <tr>
                    <td align='center' colspan="2">Choose Round</td>
                </tr>
                <tr>
                	<td>
                		<?php
                		$result_rounds = $sql->get_rounds($_SESSION['event_id']);
						$rounds_str = "<select>";
						while($row_rounds = mysql_fetch_array($result_rounds)){
							$rounds_str.= "<option>".$row_rounds['id'] . "|" . $row_rounds['round_no'] . "|" . $row_rounds['round_name'] . "</option>";
						}
						$rounds_str.="</select>";
						echo $rounds_str;
                		?>
                	</td>
                </tr>
                <tr>
                	<td>
                		Choose Candidates to Add
                	</td>
                </tr>
                <tr>
                	<td>
                		<?php

	//for male candidates
	
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
			
			//echo "<h2 align='center'>Current Round: $round_name</h2>"; 
			
			//create an array that will contain the details of candidate, and will be the basis for sorting them
			//$candidate_details[$id][$score]
			$candidate_details = array();
			$category_scores = array();
			//echo "<h3 align='center'>Overall Scores : Male Candidates</h3>";
			//enumerate them								//print categories
			//echo "<table align='center' border='1'><tr align='center'><th>Male Candidates</th>$temp_categories<th>$scoring_type</th></tr>";
			
			while($row_candidates = mysql_fetch_array($result_candidates)){
				$is_round_qualifier = $sql_control->is_round_qualifier($row_candidates['id'],$round_id);
				
				if($is_round_qualifier){
					//echo "round qualifier";
					if($sql_control->query("SELECT COUNT(*) FROM category_score WHERE candidate_id = " . $row_candidates['id']) > 0){
						//echo $row_candidates['fname'] . " exists in the table<br/>";
						//echo "<tr align='center'>";
						//echo "<td>".$row_candidates['fname']." ". $row_candidates['lname'] ."</td>";//candidate name
	
						
						
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
							$total_of_average += $average_score;
	
						}
						
						//echo "total of average $total_of_average";
						
						
						if($scoring_type=='Average'){
							//echo "<td><b>".$total_of_average/$category_count."</b></td>";
							$score = $total_of_average/$category_count;
							//echo $score."<br/>";
							//echo $row_candidates['id'];
							$candidate_details[$row_candidates['id']]=$score;
						}elseif($scoring_type=='Total'){
							//echo "<td><b>$total_of_average</b></td>";
							$score = $total_of_average;
							$candidate_details[$row_candidates['id']]=$score;
						}
						//echo "</tr>";
					}
				}

				
			}

	var_dump($candidate_details);

	
			//echo "</table>";
			//echo "<br/><br/>";
			
?>
                	</td>
                </tr>
            </table>
        </form>
    </body>
</html>