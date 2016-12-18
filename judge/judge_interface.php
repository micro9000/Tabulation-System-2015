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
                .category_name{
                  width:13%;
                  font-size: 15px;
                }
                .cand_{
                  text-align: center;
                }
              </style>
              <script type="text/javascript">
                function reloadFun(){
                  location.reload();
                }
              </script>
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
          
         <div class='container-fluid'>

             <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  
                  <th class='cand_'>Candidate</th>
                     <?php
                          $result_Category = $sql -> get_categories($_SESSION['official_event_id']);
                          while($row = mysql_fetch_array($result_Category)){
                                echo "<th class='category_name'>".$row['category_name']."</th>";
                          }
                      ?>
                  <th>Average</th>
                </tr>
                  
              </thead>
              <tbody>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <?php
                        $candidate_info_result = $sql -> get_male_candidates_info($_SESSION['official_event_id']);

                        $flag_btn_enable = true;

                        $value = "";
                        //fetch candidates 1by1
                        while ($row_candidate = mysql_fetch_array($candidate_info_result)) {
                            //check if the candidate was already scored 
                            $total_score = 0;
                            //get the candidate no
                            $cand_no_result = $sql -> get_male_candidates_no($_SESSION['official_event_id'], $row_candidate['id']);
                            $cand_no_row = mysql_fetch_array($cand_no_result);
                            //

                            $candidateImage = $sql -> get_candidate_img($_SESSION['official_event_id'], $row_candidate['id']);
                            $img = mysql_fetch_array($candidateImage);

                            

                            echo "<tr align='center'><td>".$cand_no_row['candidate_no']."</td><td>".$row_candidate['fname']." ".$row_candidate['mname']." ".$row_candidate['lname']."</td>";

                            $result_Category_two = $sql -> get_categories($_SESSION['official_event_id']);

                            //if the candidate was already scored, display the score, and the average
                            while($row_category = mysql_fetch_array($result_Category_two)){
                              $check_cand_score_result = $sql -> check_candidate_score($row_category['id'], $row_candidate['id'], $_SESSION['official_event_id'], $_SESSION['official_id'], 1);
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
                                    $insert_cand_score_result = $sql -> insert_candidate_score($row_category['id'], $row_candidate['id'], $_SESSION['official_event_id'], $_SESSION['official_id'], 1, $cat_score);
                                    //echo "<script>alert('Scores are not empty.');</script>";
                                    if($insert_cand_score_result == true){
                                      echo "<meta http-equiv=\"refresh\" content=\"0\"/>";
                                    }
                                  }
                                }
                              }
                            }
                            $cat_count = $sql->get_category_count($_SESSION['official_event_id'],1);
                            echo "<td>".$total_score/$cat_count."</td></tr>"; 

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