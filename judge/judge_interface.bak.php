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


?>
<!DOCTYPE html>
<html lang="en">
<html>
       <head>
              <title>Juding:<?php echo $event_title; ?></title>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" href="../css/bootstrap.min.css">
              <link rel="stylesheet" href="../css/home_style.css">
              <style type="text/css">
                td.td_cand_no{
                  width: 11%;
                  font-size: 15px;
                }
                td.td_cand_name{
                  width: 23%;
                  font-size: 15px;
                }
                td.td_cand_cri{
                  width: 60%;
                  font-size: 15px;
                }
                td.td_cand_total{
                  width: 6%;
                  font-size: 15px;
                }
              </style>
       </head>
       <body class="homeBG js">

        <header>
          <div class="row">
            <div class="col-sm-12">
              <div id="main-navbar" class="navbar navbar-inversenav-main" rols="navigation">
                <div class="container">
                  <ul  class="list-unstyled nav navbar-nav" id="nav-ul">
                    <li  id="nav-li">
                      <a href="home.php"><span class="glyphicon glyphicon-home"><span/><b>ProgDenTabulation</b></a></li>

                    <li  id="nav-li">
                      <a href="#"><span class="glyphicon glyphicon-user"><span/><?php echo $official_username; ?></span></a></li>

                    <li id="nav-li" class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">CANDIDATES<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="male_candidates_page.php">Male</a></li>
                        <li><a href="female_candidates_page.php">Female</a></li>
                      </ul>
                    </li>

                    <li  id="nav-li">
                      <a href="#">JUDGING INTERFACE</a></li>
                  </ul>

                  <ul class="nav navbar-nav navbar-right" id="nav-ul">
                      <li id="nav-li"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </header>
        <br/><br/><br/><br/>
          
         <div class='container'>

             <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Candidate</th>
                     <?php
                          $result_Category = $sql -> get_categories($_SESSION['official_event_id']);
                          while($row = mysql_fetch_array($result_Category)){
                                echo "<th>".$row['category_name']."</th>";
                          }
                      ?>
                  <th>Total</th>    
                </tr>
              </thead>
              <tbody>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <?php
                        $candidate_info_result = $sql -> get_male_candidates_info($_SESSION['official_event_id']);
                        
                        while ($row_candidate = mysql_fetch_array($candidate_info_result)) {
                            //check if the candidate was already scored 
                            //if the candidate was already scored, display the score, and the average
                            //Candidate Name  |src | scr| average | 
                            // Juan Dela Cruz | 10 | 10 | 10

                            //else do the following below
                            $total_global = 0;
                            $count_global = 0;
                            echo "<tr><td>".$row_candidate['fname']." ".$row_candidate['mname']." ".$row_candidate['lname']."</td>";
                            $result_Category_two = $sql -> get_categories($_SESSION['official_event_id']);
                            

                            while($row_category = mysql_fetch_array($result_Category_two)){

                              $check_cand_score_result = $sql -> check_candidate_score($row_category['id'], $row_candidate['id'], $_SESSION['official_event_id'], 1);
                              $cand_score = mysql_fetch_array($check_cand_score_result);
                              //echo $cand_score['category_id']." ".$cand_score['candidate_id']." ".$cand_score['event_id']." ".$cand_score['round_id'] ." ".$cand_score['score']."<br/>";
                                                      //cat_score_$candidateID_$categoryID
                              $total = 0;
                              $count = 0;
                              $is_candidate_score = true;
                              if($cand_score['candidate_id'] == $row_candidate['id']){
                                echo "<td>".$cand_score['score']."</td>";
                                $total += $cand_score['score'];
                                $count+=1;
                                $is_candidate_score &= true;
                                $total_global += $total;
                                $count_global += $count;
                              }else{
                                echo "<td><select name='cat_score_".$row_candidate['id']."_".$row_category['id']."'><option></option>";                              $counter = 1;
                                while($counter <= $row_category['max_score']){
                                  echo "<option>".$counter."</option>";
                                  $counter++;
                                }
                                echo "</select></td>";
                                $is_candidate_score &= false;
                              }

                              if(isset($_POST['candidate_id_'.$row_candidate['id']])){
                                  if($_POST['cat_score_'.$row_candidate['id']."_".$row_category['id']] == "")
                                    echo "No Score Added";
                                  else
                                    echo $_POST['cat_score_'.$row_candidate['id']."_".$row_category['id']];
                              }
                            }
                            if($is_candidate_score){
                              echo "<td>".$total_global/$count_global."</td>";
                              echo "</tr>";
                            }else{
                              echo "<td><input type='submit' value='Finalize' name='candidate_id_".$row_candidate['id']."' ></td>";
                              echo "</tr>";
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