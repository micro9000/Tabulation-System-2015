<?php
   include '../connection/officialSession.php';
      $official_judge_session = new officialSession();
      $official_judge_session -> officialLogin();
    
  
      if(!isset($_SESSION['official_username']) && !isset($_SESSION['official_password']) && !isset($_SESSION['official_id'])){
        header("location:index.php");
      }
      $sql = new sql_control();

      $official_username = $_SESSION['official_username'];

      $_SESSION['official_event_id']=$sql->get_event_id_for_official($official_username);

      //gets the info about event
      $result=$sql->get_event($_SESSION['official_event_id']);
      $event_information = mysql_fetch_array($result);
      $event_title = $event_information['title'];
      $event_description = $event_information['description'];
      $event_venue = $event_information['venue'];

      if(!isset($_SESSION['round_id'])){
        $_SESSION['round_id'] = $sql->get_first_round($_SESSION['official_event_id']);
        //get the round_name
        //echo "Round ID:".$_SESSION['round_id'];
        if(!(isset($_SESSION['round_title']))){//if the round title is not yet set, get the first round in the event
          $_SESSION['round_title'] = $sql->get_round_title($_SESSION['round_id']);
          //$round_title = $_SESSION['round_title'];
        }
      }

	//$scoring_type = NIL;
	if(!(isset($_SESSION['scoring_type']))){
		$_SESSION['scoring_type'] = 'Average';
		//$scoring_type = $_SESSION['scoring_type'];
	}
	  
?>
<!DOCTYPE html>
<html lang="en">
<html>
       <head> 
              <title>Judging:<?php echo $event_title; ?></title>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" href="../css/bootstrap.min.css">
              <link rel="stylesheet" href="../css/home_style.css">
              <style type="text/css">

                th.cand_no{
                  width: 5%;
                  font-size: 15px;
                  text-align: center;
                }
                th.td_cand_name, th.cand_{
                  width: 20%;
                  font-size: 15px;
                  text-align: center;
                }
                th.ave{
                  width:7%;
                  font-size: 15px;
                  text-align: center;
                }
                .category_name{
                  width:13%;
                  font-size: 15px;
                  text-align: center;
                }
                span.warn_msg{
                  color:red;
                  font-size: 12px;
                  font-style: italic;
                }
                th.msg{
                  text-align: center;
                }
              </style>
              <script type="text/javascript">
                function reloadFun(){
                  location.reload();
                }
              </script>
       </head>

        <header>
          <div class="row">
            <div class="col-sm-12">
              <div id="main-navbar" class="navbar navbar-inversenav-main" rols="navigation">
                        <div class="container">
                          <ul  class="list-unstyled nav navbar-nav" id="nav-ul">
                            <li  id="nav-li">
                              <a href="home.php"><span class="glyphicon glyphicon-home"><span/><span><b>ProgDenTabulation</b></span></a></li>

                            <li  id="nav-li">
                              <a href="#"><span class="glyphicon glyphicon-user"></span><?php echo $official_username; ?></a></li>

                            <li id="nav-li" class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Candidates <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="male_candidates_page.php">Male Candidates</a></li>
                                <li><a href="female_candidates_page.php">Female Candidates</a></li>
                              </ul>
                            </li>
                            <li  id="nav-li" class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Judging Interface <span class='caret'></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="judge_ui_male_candidates_page.php">Male Candidates</a></li>
                                <li><a href="judge_ui_female_candidates_page.php">Female Candidates</a></li>
                              </ul>
                              </li>
                          </ul>

                          <ul class="nav navbar-nav navbar-right" id="nav-ul">
                              <li id="nav-li"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
                          </ul>
                        </div>
                      </div>
          </div>
        </header>
          <body class="homeBG js">
       		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
       			<h4 align="center"> Choose Round: 
		        	<?php
		        		$result_rounds = $sql->get_rounds($_SESSION['official_event_id']);
						
						$temporary_rnds = "<select name='round_details'>";
						while($row_rounds = mysql_fetch_array($result_rounds)) //roundname|round_no|roiundid|scoringtype
							$temporary_rnds .= "<option>" . $row_rounds['round_name']."|".$row_rounds['round_no']."|".$row_rounds['id']."|".$row_rounds['scoring_type']. "</option>";
						
						echo $temporary_rnds . "</select>";
						echo "<input type='submit' name='set_rnd_id' value='Go'/>";
						if(isset($_POST['set_rnd_id'])){
							$round_details_raw = $_POST['round_details'];
							$round_details = explode("|", $round_details_raw);
							$_SESSION['round_id'] = $round_details[2];
							$_SESSION['round_title'] = $round_details[0];
							$_SESSION['scoring_type'] = $round_details[3];
							
							echo "<meta http-equiv=\"refresh\" content=\"0\"/>";
						}
		        	?>
		        </small></h4>
       		</form>
       		
       <divclass='header'>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1 style='color:brown'  align="center">Female Candidates</h1>
		<h3 align="center">  Current Round: <b><?php echo $_SESSION['round_title']; ?></b></h3>
        <br/>
       </div>
         <div class='container-fluid'>

             <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr><th colspan="10" class="msg"><span class="warn_msg">To finalize the score, just press the OK button. Scoring is done, ONE BY ONE.<br/><b>Note: </b>Once the scores are finalized, it can never be changed again.</span></th></tr>
                <tr>
                  <th class="cand_no">No.</th>
                  
                  <th class='cand_'>Candidate</th>
                     <?php
                          $result_Category = $sql -> get_categories($_SESSION['official_event_id'],$_SESSION['round_id']);
                          while($row_category = mysql_fetch_array($result_Category)){
                                echo "<th class='category_name'>".$row_category['category_name']."</th>";
                          }
                      ?>
                  <th class='ave'><?php echo $_SESSION['scoring_type']; ?></th>
                </tr>
                  
              </thead>
              <tbody>
                    <?php
                        $candidate_info_result = $sql -> get_female_candidates_info($_SESSION['official_event_id']);
	
                        $flag_btn_enable = true;

                        $value = "";
                        //fetch candidates 1by1
                        while ($row_candidate = mysql_fetch_array($candidate_info_result)) {
                        	//check first if the candidate is a round qualifier
                        	$is_round_qualifier = $sql->is_round_qualifier($row_candidate['id'],$_SESSION['round_id']);
							if($is_round_qualifier){
								
							
	                            //check if the candidate was already scored 
	                            $total_score = 0;
	                            //get the candidate no
	                            $cand_no_result = $sql -> get_female_candidates_no($_SESSION['official_event_id'], $row_candidate['id']);
	                            $cand_no_row = mysql_fetch_array($cand_no_result);
	                            //
	
	                            $candidateImage = $sql -> get_candidate_img($_SESSION['official_event_id'], $row_candidate['id']);
	                            $img = mysql_fetch_array($candidateImage);
	
	                            
	
	                            echo "<tr align='center'><td>".$cand_no_row['candidate_no']."</td><td>".$row_candidate['fname']." ".$row_candidate['mname']." ".$row_candidate['lname']."</td>";
	                                                                                        //for temporary the round id will be 1
	                            $result_Category_two = $sql -> get_categories($_SESSION['official_event_id'],$_SESSION['round_id']);
	
	                            //if the candidate was already scored, display the score, and the average
	                            while($row_category = mysql_fetch_array($result_Category_two)){
	                              $check_cand_score_result = $sql -> check_candidate_score($row_category['id'], $row_candidate['id'], $_SESSION['official_event_id'], $_SESSION['official_id'], $_SESSION['round_id']);
	                              $cand_score = mysql_fetch_array($check_cand_score_result);
	
	                              //Candidate Name  |src | scr| average | 
	                              // Juan Dela Cruz | 10 | 10 | 10
	                              if($cand_score['score']>0){
	                                echo "<td>".$cand_score['score']."</td>";//print the score and find its average-
	                                $total_score += $cand_score['score'];
	                                $flag_btn_enable = true;
	
	                              }else{//else do the following below
	                                                        //cat_score_$candidateID_$categoryID
	                                echo "<td><select name='cat_score_".$row_candidate['id']."_".$row_category['id']."' id='cat_score_".$row_candidate['id']."_".$row_category['id']."'><option></option>";
	                                $counter = 1;
	                                  while($counter <= $row_category['max_score']){
	                                    echo "<option>".$counter."</option>";
	                                    $counter++;
	                                  }
	                                echo "</select><input onclick='reloadFun()' type='submit' value='Ok' name='btn_category_id_".$row_category['id']."_".$row_candidate['id']."'/></td>";
	                                if(isset($_POST['btn_category_id_'.$row_category['id']."_".$row_candidate['id']])){
	
	                                  $cat_score = $_POST['cat_score_'.$row_candidate['id']."_".$row_category['id']];
	                                  if($_POST['cat_score_'.$row_candidate['id']."_".$row_category['id']] != ""){
	                                    $insert_cand_score_result = $sql -> insert_candidate_score($row_category['id'], $row_candidate['id'], $_SESSION['official_event_id'], $_SESSION['official_id'], $_SESSION['round_id'], $cat_score, "judge_ui_female_candidates_page.php");
	                                    //echo "<script>alert('Scores are not empty.');</script>";
	                                    
	                                    //if($insert_cand_score_result == true){
	                                      //echo "<meta http-equiv=\"refresh\" content=\"0\"/>";
	                                    //}
	                                  }
	                                }
	                              }
	                            }
	                            $cat_count = $sql->get_category_count($_SESSION['official_event_id'],$_SESSION['round_id']);
	                            if($_SESSION['scoring_type']=='Average'){
	                            	if($total_score==0)
										echo "<td>0</td></tr>";
									else {
										//echo "<td>".$total_score/$cat_count."</td></tr>";
										printf("<td>%.2f</td>",$total_score/$cat_count);
									} 
	                            }else if($_SESSION['scoring_type']=='Total'){
	                            	echo "<td>$total_score</td></tr>";
	                            }
							}

                        }
                    ?>
                </form>

              </tbody>
            </table>
        </div>

              <br/>
             
             <hr/>
              <footer class="footer">
                <div class="container">
                  <p class="text-muted">
                    <span>&copy 2015 Programmers' Den</span>
                  </p>
                  <p class="text-muted">
                    <a href="http://www.tsu.edu.ph/" target="_blank"><span>Tarlac State University |</span></a>
                    <span>College of Computer Studies</span>
                  </p >
                  <p class="text-muted">
                      <span><a href="#">Contact</a> | <a href="https://www.facebook.com/ccsprogrammersden?ref=hl" target="_blank"> Facebook</a></span>
                  </p>
                </div>
              </footer>
            
           
              <script src="../js/jquery2015.min.js"></script>
              <script src="../js/bootstrap.min.js"></script>
              

       </body>
</html>