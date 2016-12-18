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
              <title>Candidates:<?php echo $event_title; ?></title>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" href="../css/bootstrap.min.css">
              <link rel="stylesheet" href="../css/home_style.css">
       </head>
       <body class="homeBG js">

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
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Candidates<span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="male_candidates_page.php">Male Candidates</a></li>
                                <li><a href="female_candidates_page.php">Female Candidates</a></li>
                              </ul>
                            </li>
                            <li  id="nav-li" class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Judging Interface<span class='caret'></span></a>
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
          </div>
        </header>

        <div class="jumbotron">
          <div class="row-fluid">
            <div class="row">
              <div class="col-md-12">
                <h1>Male Candidates</h1>
              </div>
            </div>
          </div>
        </div>

        <!--
        <div class="container">
          <div class="row">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <?php
                $candidateInfo = $sql->get_female_candidates_info($_SESSION['official_event_id']);
                while($row=mysql_fetch_array($candidateInfo)){
                  echo "<div class='col-lg-3 col-md-4 col-xs-6 thumb '>";
                    echo "<div class='thumbnail'>";
                      echo "<a href='#' data-toggle='modal' data-target='#candProfile'>";
                        echo "<div class='caption text-center can_name'><span class='can_name_s'>".$row['fname'] . " " . $row['lname']."</span></div>";
                          $candidateImage = $sql -> get_candidate_img($_SESSION['official_event_id'], $row['id']);
                          $img = mysql_fetch_array($candidateImage);
                        echo "<img src='".$img['img_path']. $img['img_name'] ."' class='can_gallery' name='view_cand_pro'>";
                      echo "</a>";
                    echo "</div>";
                  echo "</div>";
                }
              ?>
            </form>
        </div>

          </div>
      -->
        <div class="container">
          <div class="row">
            <?
              //get the male_candidates
              $candidateInfo = $sql->get_male_candidates_info($_SESSION['official_event_id']);
              while($row = mysql_fetch_array($candidateInfo)){
                echo "<div class='col-lg-6 col-md-6 col-xs-6 thumb'>";
                  echo "<div class='thumbnail'>";
                    echo "<div class='row'>";
                      echo "<div class='col-md-12'>";
                        echo "<div class='row'>";
                          echo "<div class='col-md-6'>";
                            //get the candidate_no
                            $candidateNo = $sql -> get_male_candidates_no($_SESSION['official_event_id'], $row['id']);
                            $cand_male_no = mysql_fetch_array($candidateNo);
                            //get the candidate_images
                            $candidateImage = $sql -> get_candidate_img($_SESSION['official_event_id'], $row['id']);
                            $img = mysql_fetch_array($candidateImage);

                            echo "<img src='".$img['img_path']. $img['img_name'] ."' class='can_gallery' name='view_cand_pro'>";
                          echo "</div>";
                          echo "<div class='col-md-6 col-sm-6'>";
                              echo "<p class='info'><span>No : </span>".$cand_male_no['candidate_no']."</p>";
                              echo "<p class='info'><span>Name : </span>".$row['fname'] . " ".$row['mname']." ". $row['lname'] ."</p>";
                              echo "<p class='info'><span>Age : </span>".$row['age']."</p>";
                              echo "<p class='info'><span>Height : </span>".$row['height']."</p>";
                              echo "<p class='info'><span>Vitals : </span>".$row['vitals']."</p>";
                              echo "<p class='info'><span>Address : </span>".$row['address']."</p>";
                          echo "</div>";
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";
                echo "</div>";
              }
            ?>

          </div>
        </div>

            <div class="modal fade" id="candProfile" role="dialog">
              <div class="modal-dialog">

                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Profile</h4>
                  </div>
                  <div class="modal-body">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="thumbnail">
                            <img src="../images/missWorld2014/Olivia_Culpo.jpg">
                          </div>
                        </div>
                        <div>
                          
                        </div>
                        <div class="col-sm-8">
                          <p>Some Text here in the modal body</p>
                        </div>
                      </div>
                    </div>
                  </div>
                    <div class="modal-footer">
                      <button type="button" data-toggle="tab" class="btn btn-default pull-left">Previous</button>
                      <button type="button" data-toggle="tab" class="btn btn-default pull-right">Next</button>
                    </div>
                </div>
              </div>
            </div>
        </div>
              <br/>
             
             <hr/>
              <footer class="footer">
                <div class="container">
                  <p class="text-muted">
                    <span>&copy 2015 Programmers' Den</span>
                  </p>
                  <p class="text-muted">
                    <a href="http://www.tsu.edu.ph/"><span>Tarlac State University |</span></a>
                    <span>College of Computer Studies</span>
                  </p >
                  <p class="text-muted">
                      <span><a href="#">Contact</a> | <a href="https://www.facebook.com/ccsprogrammersden?ref=hl" target="_blank"> Facebook</a></span>
                  </p>
                </div>
              </footer>
            </nav>
            <!--
            <script type="text/javascript">
                $(document).ready(function(){
                  $("a").click(function(){
                    $("#candInfo").slideDown("slow");
                  })
                })
            </script>
            <style type="text/css">
              #candInfo{
                padding: 10px;
                display: none;
              }
            </style>
            -->
              <script src="../js/jquery2015.min.js"></script>
              <script src="../js/bootstrap.min.js"></script>
       </body>
</html>