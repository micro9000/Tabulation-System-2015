<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
    
    if(isset($_POST['btn_view_event'])){
        $event_arr = explode('|', $_POST['event_id']);
        $event_id = $event_arr[0];
        $event_title = $event_arr[1];
        $_SESSION['event_id'] = $event_id;
        $_SESSION['event_title'] = $event_title;

        $dir_name = $_SESSION['event_id']."-".$_SESSION['event_title'];
        //Session for Folder name

        //Create full path and directory name
        $dir_name_two = "../images/uploads/".$dir_name;
					
		if (is_dir($dir_name_two) === false){
		    mkdir($dir_name_two);
		}
		$_SESSION['event_folder'] = $dir_name;
    }
    
	$id_admin = $_SESSION['admin_id'];
    $sql = new sql_control();
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Pageant Details</title>
		<meta charset="uft-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <script type="text/javascript" src="../js/jquery2015.min.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <style type="text/css">
        td.a{
        	width:10%;
        	font-size: 15px;
        }
        table{
        	width:50%;
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <? echo "Admin ID : $id_admin | Event ID: " . $_SESSION['event_id']; ?>
                    <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <th colspan="6"><?php echo "Event : <b>" . $_SESSION['event_title'] . "</b>";  ?></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='a'><h4>Rounds</h4></td><td class='a'><a href="addRounds.php" class='btn btn-info btn-lg'>ADD</a></td>
                                    <td class='a'><h4>Candidates</h4></td><td class='a'><a href="addCandidates.php" class='btn btn-info btn-lg'>ADD</a></td>
                                    <td class='a'><h4>Judges</h4></td><td class='a'><a href="add_judge.php" class='btn btn-info btn-lg'>ADD</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="5">
                                    Rounds
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>id</td>
                                <td>No</td>
                                <td>Round title</td>
                                <td>Event id</td>
                                <td>Scoring Type</td>
                            </tr>
                                <?
                                    if(!($sql->getRoundCount($_SESSION['event_id'])>0)){
                                        echo "<td colspan='5'>No rounds added yet.</td>";
                                    }else{
                                        $result = $sql -> getRounds($_SESSION['event_id']);
                                        while($round_row = mysql_fetch_array($result)){
                                            echo "<tr>";
                                            echo "<td>".$round_row['id']."</td>";
                                            echo "<td>".$round_row['round_no']."</td>";
                                            echo "<td>".$round_row['round_name']."</td>";
                                            echo "<td>".$round_row['event_id']."</td>";
                                            echo "<td>".$round_row['scoring_type']."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    
                                ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="6">
                                    All Male Candidate
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>No.</td>
                                <td>Name</td>
                                <td>age</td>
                                <td>Height</td>
                                <td>Vitals</td>
                                <td>Address</td>
                            </tr>
                            <?
                                if(!($sql->get_candidate_count_no($_SESSION['event_id'])>0)){
                                    echo "<td colspan='2'>No added candidates yet.</td>";
                                }else{
                                   $candidate_male_info = $sql -> get_male_candidates_info($_SESSION['event_id']);
                                    while ($male_row = mysql_fetch_array($candidate_male_info)){
                                        $male_no = $sql -> get_male_candidates_no($_SESSION['event_id'], $male_row['id']);
                                        $male_no_row = mysql_fetch_array($male_no);
                                        echo "<tr>";
                                            echo "<td>".$male_no_row['candidate_no']."</td>";
                                            echo "<td>".$male_row['fname']." ".$male_row['lname']." ".$male_row['mname']."</td>";
                                            echo "<td>".$male_row['age']."</td>";
                                            echo "<td>".$male_row['height']."</td>";
                                            echo "<td>".$male_row['vitals']."</td>";
                                            echo "<td>".$male_row['address']."</td>";
                                        echo "</tr>";
                                    } 
                                }
                                
                            ?>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="6">
                                    All Female Candidate
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>No.</td>
                                <td>Name</td>
                                <td>age</td>
                                <td>Height</td>
                                <td>Vitals</td>
                                <td>Address</td>
                            </tr>
                            <?
                                if(!($sql->get_candidate_count_no($_SESSION['event_id'])>0)){
                                    echo "<td colspan='2'>No added candidates yet.</td>";
                                }else{
                                    $candidate_female_info = $sql -> get_female_candidates_info($_SESSION['event_id']);
                                    while ($female_row = mysql_fetch_array($candidate_female_info)){
                                        $female_no = $sql -> get_female_candidates_no($_SESSION['event_id'], $female_row['id']);
                                        $female_no_row = mysql_fetch_array($female_no);
                                        echo "<tr>";
                                            echo "<td>".$female_no_row['candidate_no']."</td>";
                                            echo "<td>".$female_row['fname']." ".$male_row['lname']." ".$male_row['mname']."</td>";
                                            echo "<td>".$female_row['age']."</td>";
                                            echo "<td>".$female_row['height']."</td>";
                                            echo "<td>".$female_row['vitals']."</td>";
                                            echo "<td>".$female_row['address']."</td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="6">
                                    Judges
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td>Username</td>
                                <td>Alias</td>
                                <td>Gender</td>
                                <td>Admin Creator</td>
                            </tr>
                            <?
                                $judge_info = $sql -> get_officials($_SESSION['event_id']);
                                while ($judge = mysql_fetch_array($judge_info)){
                                    echo "<tr>";
                                        echo "<td>".$judge['id']."</td>";
                                        echo "<td>".$judge['username']."</td>";
                                        echo "<td>".$judge['alias']."</td>";
                                        echo "<td>".$judge['gender']."</td>";
                                        echo "<td>".$judge['admin_creator_id']."</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
		<!-- <script type="text/javascript" src="js/date_time.js"></script> -->
	</body>
</html>
