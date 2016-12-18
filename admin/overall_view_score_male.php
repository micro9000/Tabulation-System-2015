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
		<title>View Scores Male</title>
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
	<form action='overall_view_score_male.php' method='post'>
		<a href="view_scores.php">Back</a>
<?php
	echo "<div class='container'>";
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
				$temp_categories .= "<th class='lbl'>" . $row_categories['category_name'] . "</th>";
			}
			echo "<h4 align='center'>Event: <b>" . $_SESSION['event_title']."</b></h4>";
			echo "<h4 align='center'>Current Round: $round_name</h4>"; 
			
			//create an array that will contain the details of candidate, and will be the basis for sorting them
			//$candidate_details[$id][$score]
			$candidate_details = array();
			$category_scores = array();
			echo "<h3 align='center'>Overall Scores : Male Candidates</h3>";
			//enumerate them								//print categories
			echo "<table align='center' border='1' class='table table-bordered table-hover'>";
			//echo "<tr><th class='lbl'>Male Candidates</th><th>$temp_categories</th><td>$scoring_type</th></tr>";
			echo "<tr><th class='lbl'>Male Candidates</th>$temp_categories</tr>";
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
						
						echo "</tr>";
					}
				}

				
			}

			
			echo "</table>";
	}
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
	       	echo "";
	       	echo $temp;
	        echo "<input type='submit' name='btn_view_scores' value='View'/>";
					
		//echo "<input type='"
	    }

	echo "</div>";
?>
	<hr/>
	        <?php
            
        ?>
	</form>
</body>
</html>