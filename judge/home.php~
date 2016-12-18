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
              <title>Home:<?php echo $event_title; ?></title>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" href="../css/bootstrap.min.css">
              <link rel="stylesheet" href="../css/home_style.css">
       </head>
       <body class="homeBG">
              <div class="container-fluid">
                  <div class="row"> 
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="span10">
                            <div class="span2">
                              <div id="candidates" class="carousel slide" data-ride="carousel">
                              <!-- Indicators -->
                                <ol class="carousel-indicators">
                                  <li data-target="#candidates" data-slide-to="0" class="active"></li>
                                  <li data-target="#candidates" data-slide-to="1"></li>
                                </ol>
                                      <!-- Wrapper for slides -->
                               <!-- <div class="carousel-inner" role="listbox">
                                  <div class="item active autoSize event-slider-autosize">
                                      <img class="img-responsive" src="../images/missWorld2014/LOGO.png" alt="Chania" width="100%" height="100%">
                                      <div class="carousel-caption" style="color:white">
                                        <h1 class="event_title"><b><?php echo $event_title; ?></b></h1>
                                        <p><b><?php echo $event_description . " happening at ";echo $event_venue;?></b></p>
                                      </div>
                                   </div>
				-->
                                  <div class="item autoSize event-slider-autosize">
                                      <img src="../images/missWorld2014/cover.jpg" alt="Chania" width="100%" height="100%">
                                      <div class="carousel-caption" style="color:white">
                                          <h1 class="event_title"><b><?php echo $event_title; ?></b></h1>
                                          <p><b><?php echo $event_description . " happening at ";echo $event_venue;?></b></p>
                                      </div>
                                  </div>                                                        
                                </div>
                                    <!-- Left and right controls -->
                                    <a class="left carousel-control" href="#candidates" role="button" data-slide="prev">
                                      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                      <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#candidates" role="button" data-slide="next">
                                      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                      <span class="sr-only">Next</span>
                                    </a>
                              </div>
                            </div>
                          </div>
                        </div>          
                      </div>
                   </div>
            </div>
                      <div id="main-navbar" class="navbar navbar-inversenav-main" rols="navigation">
                        <div class="container">
                          <ul  class="list-unstyled nav navbar-nav" id="nav-ul">
                            <li  id="nav-li">
                              <a href="home.php"><span class="glyphicon glyphicon-home"><span/><span><b>ProgDenTabulation</b></span></a></li>

                            <li  id="nav-li">
                              <a><span class="glyphicon glyphicon-user"></span><?php echo $official_username; ?></a></li>

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
                      <div class="jumbotron">
                        <div class="row-fluid">
                          <div class="row">
                            <div class="col-md-12">
                              <h1>Tarlac State University Colleges</h1>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="container">
                        <div class="row">
                          <div class="col-md-6">
                             <img src="../images/logos/CASS_150x150.jpg.png" class="img-circle" alt="College of Arts and Social Science" width="170" height="160">
                             <span>College of Arts and Social Science</span><br/>
                             <img src="../images/logos/CBA.png" class="img-rounded" alt="College of Business and Accountancy" width="170" height="160">
                             <span>College of Business and Accountancy</span><br/>
                             <img src="../images/logos/CCS.png" class="img-circle" alt="College of Computer Studies" width="170" height="160">
                             <span>College of Computer Studies</span><br/>
                             <img src="../images/logos/COE.png" class="img-rounded" alt="College of Engineering" width="170" height="160">
                             <span>College of Engineering</span><br/>
                             <img src="../images/logos/CAFA.png" class="img-rounded" alt="College of Engineering" width="170" height="160">
                             <span>College of Architecture and Fine Arts</span><br/>
                          </div>
                          <div class="col-md-6">
                             <img src="../images/logos/COL.png" class="img-rounded" alt="Cinque Terre" width="170" height="160">
                             <span>College of Law & Criminal Justice Education</span><br/>
                             <img src="../images/logos/CPA.png" class="img-circle" alt="Cinque Terre" width="170" height="160">
                             <span>College of Public Administration</span><br/>
                             <img src="../images/logos/COED.png" class="img-circle" alt="Cinque Terre" width="170" height="160">
                             <span>College of Education</span><br/>
                             <img src="../images/logos/COS.png" class="img-circle" alt="Cinque Terre" width="170" height="160">
                             <span>College of Science</span><br/>
                             <img src="../images/logos/CT.png" class="img-circle" alt="Cinque Terre" width="170" height="160">
                             <span>College of Of Technology</span><br/>
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
                    <a href="http://www.tsu.edu.ph/" target="_blank"><span>Tarlac State University |</span></a>
                    <span>College of Computer Studies</span>
                  </p >
                  <p class="text-muted">
                      <span><a href="#">Contact</a> | <a href="https://www.facebook.com/ccsprogrammersden?ref=hl" target="_blank"> Facebook</a></span>
                  </p>
                </div>
              </footer>
            </nav>
            
              <script src="../js/jquery2015.min.js"></script>
              <script src="../js/bootstrap.min.js"></script>
       </body>
</html>

