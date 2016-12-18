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
	/*if(!isset($_SESSION['event_title'])){
		header("location:new_pageant.php");
	}*/
	$id_admin = $_SESSION['admin_id'];
	//echo "Admin ID: $id_admin | ";
    //echo "Event ID: " . $_SESSION['event_id'];
	$sql = new sql_control();


?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Add Candidates</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style type="text/css">
        table.t_cand{
            width: 100%;
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
                <li class="active"><a href="adminForm.php">Home</a></li>
                <li><a href="#">Admin : <? echo $_SESSION['admin_username'] ?></a></li>
                <li><a href="view_scores.php">View scores</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>

		<table align="center">
            <tr>
                <td><a href="main_pageant_form.php">&laquo;Back</a></td>
                <td><a href='upload_img.php'>Upload Images&raquo;</a></td>
            </tr>
            <tr>
                <td colspan="4" align="center"><h3><? echo "Event: <b>" . $_SESSION['event_title'] ."</b>";?></h3></td>
            </tr>
		</table>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <form action="addCandidates.php" method="post" enctype="multipart/form-data" name="addroom">
                        <table class='table table-striped table-bordered table-hover t_cand'>
                            <tr>
                                <td colspan="2" align="center"><b>Add Candidate</b></td>
                            </tr>
                            <!-- temporary -->
                            <!--<tr><td>Candidate No:</td><td><input type="text" name="candidate_no" /></td></tr>-->
                            <tr>
                                <td>First Name:</td><td><input type="text" name="fname" /></td>
                            </tr>
                            <tr><td>Last Name:</td><td><input type="text" name="lname" /></td></tr>
                            <tr><td>Middle Name:</td><td><input type="text" name="mname" /></td></tr>
                            <tr>
                                <td>Age</td>
                                <td>
                                    <select name="age">
                                        <?php
                                            for($i = 15; $i < 35; $i++){
                                                echo "<option>$i</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>
                                    <select name="gender">
                                        <option></option>
                                        <option>Female</option>
                                        <option>Male</option>
                                        <option>Other</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td>Height:</td><td><input type="text" name="height" /></td></tr>
                            <tr><td>Vitals:</td><td><input type="text" name="vitals" /></td></tr>
                            <tr><td>From:</td><td><input type="text" name="from" /></td></tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" class="btn btn-md btn-info" name="btn_add_candidate" value="Add" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
                 <?php
                    if(!($sql->get_candidate_count_no($_SESSION['event_id'])>0))
                        echo 'No added candidates yet.';
                    else{
                            echo "<div class='col-md-4'>";
                                echo "<table class='table table-striped table-bordered table-hover t_cand'>";
                                    echo "<thead><tr><th colspan='5'>Male Candidates</th></tr></thead>";
                                    echo "<tbody>";
                                    echo "<tr><td><b>NO.</b></td><td><b>Name</b></td><td><b>Height</b></td><td><b>Vitals</b></td><td><b>Address</b></td></tr>";
                                    $candidate_info = $sql -> get_male_candidates_info($_SESSION['event_id']);
                                    while($row = mysql_fetch_array($candidate_info)){
                                        $cand_no_result = $sql -> get_male_candidates_no($_SESSION['event_id'], $row['id']);
                                        $cand_no = mysql_fetch_array($cand_no_result);

                                        echo "<tr><td>".$cand_no['candidate_no']."</td>";
                                        echo "<td>".$row['fname']." ".$row['lname']." ".$row['mname']."</td>";
                                        echo "<td>".$row['height']."</td>";
                                        echo "<td>".$row['vitals']."</td>";
                                        echo "<td>".$row['address']."</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                echo "</table>";
                            echo "</div>";

                            echo "<div class='col-md-4'>";
                                echo "<table class='table table-striped table-bordered table-hover t_cand'>";
                                    echo "<thead><tr><th colspan='5'>Female candidates</th></tr></thead>";
                                    echo "<tbody>";
                                    echo "<tr><td><b>NO.</b></td><td><b>Name</b></td><td><b>Height</b></td><td><b>Vitals</b></td><td><b>Address</b></td></tr>";
                                    $candidate_info = $sql -> get_female_candidates_info($_SESSION['event_id']);
                                    while($row = mysql_fetch_array($candidate_info)){
                                        $cand_no_result = $sql -> get_female_candidates_no($_SESSION['event_id'], $row['id']);
                                        $cand_no = mysql_fetch_array($cand_no_result);

                                        echo "<tr><td>".$cand_no['candidate_no']."</td>";
                                        echo "<td>".$row['fname']." ".$row['lname']." ".$row['mname']."</td>";
                                        echo "<td>".$row['height']."</td>";
                                        echo "<td>".$row['vitals']."</td>";
                                        echo "<td>".$row['address']."</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                echo "</table>";
                            echo "</div>";
                    }
                 ?>
        </div>
    </div>
              <script src="../js/jquery2015.min.js"></script>
              <script src="../js/bootstrap.min.js"></script>
              
    </body>
  </html>
  
  <?php
    if(isset($_POST['btn_add_candidate'])){
        //echo 'btn add_criteria was clicked';
        
        if($_POST['fname']!="" && $_POST['lname']!=""  && $_POST['mname']!=""  && $_POST['gender']!=""  && $_POST['height']!=""  && $_POST['vitals']!=""  && $_POST['from']!=""){
            //echo "Forms were complete";

            $mysquery = "SELECT COUNT(id) as id FROM candidate_info WHERE fname = '".$_POST['fname']."' AND " . 
                        " lname = '" . $_POST['lname'] . "' AND mname = '" . $_POST['mname'] . "' AND gender='".$_POST['gender']."' AND event_id = " . $_SESSION['event_id'] ;
            $result = $sql->query($mysquery);

            //echo $mysquery;
            $row = mysql_fetch_array($result);
            if(($row['id']==0)){

                echo "Not yet in table";
                                    //get the current count of candidate
                                    //and add one
                $candidate_num = $sql->get_candidate_count($_SESSION['event_id'],$_POST['gender']);
                $sql->add_candidate( ($candidate_num+1) ,$_POST['fname'], $_POST['lname'],$_POST['mname'],$_POST['age'],$_POST['gender'],$_POST['height'],$_POST['vitals'],$_POST['from'],$_SESSION['event_id']);
                
				$query_id = "SELECT id FROM candidate_info WHERE fname = '".$_POST['fname']."' AND " . 
                        " lname = '" . $_POST['lname'] . "' AND mname = '" . $_POST['mname'] . "' AND gender='".$_POST['gender']."' AND event_id = " . $_SESSION['event_id'] ;
				
				//get the id of candidate after inserting it
				$result_id = $sql->query($query_id);
				$row = mysql_fetch_array($result_id);
				$candidate_id = $row['id'];
				//get the id of the first round in the event
				$round_id = $sql->get_first_round($_SESSION['event_id']);
				
				//echo "<script>alert($round_id);</script>";
				
				$query_round_qualifiers = "INSERT INTO round_qualifiers(round_id,candidate_id,event_id) VALUES($round_id,$candidate_id,". $_SESSION['event_id'].")";
				$result_round_qualifiers = $sql->query($query_round_qualifiers);
				
				echo "<script>window.open('addCandidates.php','_self')</script>";
                //header('location:addCandidates.php');
            }   
            else
                echo "Already exists in the table.";
        }else
            echo "<h4><p style='color:red'>Please complete the form.</p></h4>";
    }
  ?>