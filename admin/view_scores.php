<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
    if(!(isset($_SESSION['event_id'])))
        header('location:adminForm.php');
	
	$id_admin = $_SESSION['admin_id'];
	$sql_control = new sql_control();
	
	
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>View Scores</title>
		 <meta charset="uft-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <script type="text/javascript" src="../js/jquery2015.min.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <style type="text/css">

            select{
                width: 20%;
                border:2px solid black;
                padding: 10px;
                font-size: 15px;
                cursor: pointer;
                border-radius:5px;
                margin-bottom: 15px;
            }
            ._title{
                font-size: 25px;
            }
            .lbl{
            	text-align: center;
            }
        </style>
	</head>
	<body>

        <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
              </button>
              <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-fire"></span> ProgDenTabulation</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="nav navbar-nav">
                <li><a href="adminForm.php">Home</a></li>
                <li><a href="#">Admin : <? echo $_SESSION['admin_username'] ?></a></li>
                <li><a href="overall_view_score.php">Overall View</a></li>
                <li><a href="overall_view_score_male.php">Overall View Male</a></li>
                <li><a href="overall_view_score_female.php">Overall View Female</a></li>
                <li><a href="main_pageant_form.php">Back</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>
		<!--<? echo "Admin ID: $id_admin | Event ID: " . $_SESSION['event_id']; ?>-->
		<h3>View Scores</h3>
        
        <?php
            echo "Event: <b>" . $_SESSION['event_title'] ."</b></br></br>";
            
            if(!($sql_control->getRoundCount($_SESSION['event_id'])>0))
                echo "No rounds added yet.</br></br>";
            else{
            	$result = $sql_control->getRounds($_SESSION['event_id']);
            	$temp = "<select name='results'>";
            	while($row = mysql_fetch_array($result)){
            							//round_id			//round_title			//round_no
            		$temp .= "<option>".$row['id']. "|" . $row['round_name'] . "|" . $row['round_no'] . "|". $row['scoring_type'] .  "</option>";
            	}
            	$temp .= "</select>";
            	echo "<form action='view_scores.php' method='post'>";
            	echo $temp;
                echo "<input type='submit' name='btn_view_scores' value='View'/>";
                echo "<input type='submit' name='btn_print_male_scores' value='Print Male Scores'/>";
                echo "<input type='submit' name='btn_print_female_scores' value='Print Female Scores'/>";

                echo "</form>";
				//echo "<input type='"
            }
        ?>
	</body>
</html>

<?php
	echo "<div class='container'>";
	//for male candidates
	if(isset($_POST['btn_print_male_scores'])){
		$round_details = explode("|", $_POST['results']);
		$round_id = $round_details[0];
		$_SESSION['round_id_print'] = $_POST['results'];
		header('Location:print_scores.php');
	}
	if(isset($_POST['btn_print_female_scores'])){
		$round_details = explode("|", $_POST['results']);
		$round_id = $round_details[0];
		$_SESSION['round_id_print'] = $_POST['results'];
		header('Location:print_female_scores.php');
	}
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
				$temp_categories .= "<th class='lbl'>" . $row_categories['category_name'] . "</th>";
			}
			
			echo "<h2 align='center'>Current Round: $round_name</h2>"; 
			
			//create an array that will contain the details of candidate, and will be the basis for sorting them
			//$candidate_details[$id][$score]
			$candidate_details = array();
			$category_scores = array();
			echo "<h3 align='center'>Overall Scores : Male Candidates</h3>";
			//enumerate them								//print categories
			echo "<table align='center' border='1' class='table table-bordered table-hover'>";
			echo "<tr><th>No.</th><th class='lbl'>Male Candidates</th>$temp_categories<th>$scoring_type</th></tr>";
			
			while($row_candidates = mysql_fetch_array($result_candidates)){
				$is_round_qualifier = $sql_control->is_round_qualifier($row_candidates['id'],$round_id);
				$cand_male_no = $sql_control -> get_male_candidates_no($_SESSION['event_id'], $row_candidates['id']);
				$cand_male_no_row = mysql_fetch_array($cand_male_no);
				//echo "AFTER ISROUND QUALIFIER<BR/>";
				if($is_round_qualifier){
					//echo "INSIDE QUESTION ISROUND QUALIFIER";
					//echo "round qualifier";
					if($sql_control->query("SELECT COUNT(*) FROM category_score WHERE candidate_id = " . $row_candidates['id']) > 0){
						//echo $row_candidates['fname'] . " exists in the table<br/>";
						echo "<tr>";
						echo "<td class='lbl'>".$cand_male_no_row['candidate_no']."</td>";
						echo "<td class='lbl'>".$row_candidates['fname']." ". $row_candidates['lname'] ."</td>";//candidate name
	
						
						
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
							printf("<td class='lbl'><b>%.2f/%d</b> (%d/%d)</td>",$average_score,$max_score,$judges_who_scored,$judges_cnt);
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
		echo "</div>";
		
		echo "<div class='container'>";
			echo "<div class='row'>";
					echo "<div class='col-md-2'></div>";
					echo "<div class='col-md-8'>";
					//var_dump($candidate_details) . "<br/>";
					
					//computes the overall score
					echo "<h3 align='center'>Overall Ranking</h3>";
					arsort($candidate_details);
					echo "<table align='center' class='table table-bordered table-hover' border='1'><tr><th class='lbl'>Rank</th><th class='lbl'>Name</th><th class='lbl'>Overall Score</th></tr>";
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
						
						 
						//add to overall score																	//assumed that the gender is male
						echo $sql_control->add_to_overallscore($id, $_SESSION['event_id'], $round_id,$candidate_score,"Male");
						
						
						
						//collect the candidate ranks 
													//$ranks|$event_id|$round_id
						$ranks .= "<option>$rank|". $_SESSION['event_id'] ."|$round_id|Male</option>";
						//create a combo box for generating top 5
						
						$rank++;
					}
					//if the the next round is final, their will be no need to add candidates to the next round 
					if(!($scoring_type=='Total')){
						$ranks.="</select>";
						//display the ui for adding top candidats
						echo "<form action='add_top_candidates.php' method='post'>";
						echo "<tr><td colspan='3' align='center'>Add top candidates: $ranks <input type='submit' value='Ok'/></td></tr>";
						echo "</form>";
					}
					
					echo "</table>";
					echo "<div class='col-md-2'></div>";
				echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "<div class='container'>";
				
					echo "<br/>";
					echo "<h3 align='center' >Per-Category Rankings</h3>";
					//segments the scores per category
					//$category_scores[$category_id][$candidate_id] = $average_score;
					foreach ($category_scores as $category_id => $candidate) {
						echo "<div class='row'>";
						//echo $category_id . "<br/>";
						//segments the scores per category
						$scoring_details = $sql_control -> get_scoring_details($category_id, $candidate_id, $event_id, $round_id);
						//get the category info
						$category_row = $sql_control->get_category_info($category_id);
						$rank=1;
						echo "<div class='col-md-3'></div>";
						echo "<div class='col-md-6'>";
						echo "<table align='center' class='table table-bordered table-hover'><tr><th class='lbl'>Rank</th><th class='lbl'>".$category_row['category_name']."</th><th class='lbl'>Score</th></tr>";
						
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
						echo "</div>";
						echo "<div class='col-md-3'></div>";
						echo "</div>";
						echo "<br/>";
					}
				
			echo "</div>";
			
	}

	echo "<hr/>";
	
	
?>



<?php
	echo "<div class='container'>";
	//for female candidates
	if(isset($_POST['btn_view_scores'])){
			//$round_details = explode('|',$_POST['results']);
			$round_details = explode("|", $_POST['results']);
			$round_id = $round_details[0];
			$round_name = $round_details[1];
			$round_no = $round_details[2];
			$scoring_type = $round_details[3];
			
			//var_dump($round_details);

			//get the candidates
			$result_candidates = $sql_control->get_female_candidates_info($_SESSION['event_id']);

			//get categories
			$result_categories = $sql_control->get_categories($_SESSION['event_id'],$round_id);
			$temp_categories = "";
			while($row_categories = mysql_fetch_array($result_categories)){
				$temp_categories .= "<th class='lbl'>" . $row_categories['category_name'] . "</th>";
			}
			
			echo "<h2 align='center'>Current Round: $round_name</h2>"; 
			
			//create an array that will contain the details of candidate, and will be the basis for sorting them
			//$candidate_details[$id][$score]
			$candidate_details = array();
			$category_scores = array();
			echo "<h3 align='center'>Overall Scores : Female Candidates</h3>";
			//enumerate them								//print categories
			echo "<table align='center' border='1' class='table table-bordered table-hover'>";
			echo "<tr><th class='lbl'>Female Candidates</th>$temp_categories<th>$scoring_type</th></tr>";
			
			while($row_candidates = mysql_fetch_array($result_candidates)){
				$is_round_qualifier = $sql_control->is_round_qualifier($row_candidates['id'],$round_id);
				//echo "AFTER ISROUND QUALIFIER<BR/>";
				if($is_round_qualifier){
					//echo "INSIDE QUESTION ISROUND QUALIFIER";
					//echo "round qualifier";
					if($sql_control->query("SELECT COUNT(*) FROM category_score WHERE candidate_id = " . $row_candidates['id']) > 0){
						//echo $row_candidates['fname'] . " exists in the table<br/>";
						echo "<tr>";
						echo "<td class='lbl'>".$row_candidates['fname']." ". $row_candidates['lname'] ."</td>";//candidate name
	
						
						
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
							printf("<td class='lbl'><b>%.2f/%d</b> (%d/%d)</td>",$average_score,$max_score,$judges_who_scored,$judges_cnt);
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
		echo "</div>";
		
		echo "<div class='container'>";
			echo "<div class='row'>";
					echo "<div class='col-md-2'></div>";
					echo "<div class='col-md-8'>";
					//var_dump($candidate_details) . "<br/>";
					
					//computes the overall score
					echo "<h3 align='center'>Overall Ranking</h3>";
					arsort($candidate_details);
					echo "<table align='center' class='table table-bordered table-hover' border='1'><tr><th class='lbl'>Rank</th><th class='lbl'>Name</th><th class='lbl'>Overall Score</th></tr>";
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
						
						 
						//add to overall score																	//assumed that the gender is female
						echo $sql_control->add_to_overallscore($id, $_SESSION['event_id'], $round_id,$candidate_score,"Female");
						
						
						
						//collect the candidate ranks 
													//$ranks|$event_id|$round_id|gender
						$ranks .= "<option>$rank|". $_SESSION['event_id'] ."|$round_id|Female</option>";
						//create a combo box for generating top 5
						
						$rank++;
					}
					//if the the next round is final, their will be no need to add candidates to the next round 
					if(!($scoring_type=='Total')){
						$ranks.="</select>";
						//display the ui for adding top candidats
						echo "<form action='add_top_candidates.php' method='post'>";
						echo "<tr><td colspan='3' align='center'>Add top candidates: $ranks <input type='submit' value='Ok'/></td></tr>";
						echo "</form>";
					}
					
					echo "</table>";
					echo "<div class='col-md-2'></div>";
				echo "</div>";
			echo "</div>";
	echo "</div>";
	echo "<div class='container'>";
		
			echo "<br/>";
			echo "<h3 align='center' >Per-Category Rankings</h3>";
			//segments the scores per category
			//$category_scores[$category_id][$candidate_id] = $average_score;
			foreach ($category_scores as $category_id => $candidate) {
				echo "<div class='row'>";
				//echo $category_id . "<br/>";
				//segments the scores per category
				$scoring_details = $sql_control -> get_scoring_details($category_id, $candidate_id, $event_id, $round_id);
				//get the category info
				$category_row = $sql_control->get_category_info($category_id);
				$rank=1;
				echo "<div class='col-md-3'></div>";
				echo "<div class='col-md-6'>";
				echo "<table align='center' class='table table-bordered table-hover'><tr><th class='lbl'>Rank</th><th class='lbl'>".$category_row['category_name']."</th><th class='lbl'>Score</th></tr>";
				
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
				echo "</div>";
				echo "<div class='col-md-3'></div>";
				echo "</div>";
				echo "<br/>";
			}
		
	echo "</div>";
			
	}

	echo "<hr/>";
	
	
?>
