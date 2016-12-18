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
	$sql_control = new sql_control();
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
                <li><a href="adminForm.php">Home</a></li>
                <li><a href="#">Admin : <? echo $_SESSION['admin_username'] ?></a></li>
                
                <li><a href="main_pageant_form.php">Pageant Details</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>

<html>
	<head>
		<title>Round Details</title>

		<style type="text/css">
			.lbl{
				width: 20%;
				text-align: center;
			}

            select{
                width: 20%;
                border:2px solid black;
                padding: 10px;
                font-size: 15px;
                cursor: pointer;
                border-radius:5px;
                margin-bottom: 15px;
            }
            .inp{
            	text-align: center;
            }
		</style>
	</head>
	<body>
        <div class="container">
    	<a href="main_pageant_form.php">&laquo;Back</a>
        <?php
            echo "<h2>Event: <b>" . $_SESSION['event_title'] ."</b></h2></br></br>";
            
            if(!($sql_control->getRoundCount($_SESSION['event_id'])>0))
                echo "No rounds added yet.</br></br>";
            else{
            	$result = $sql_control->getRounds($_SESSION['event_id']);
            	$temp = "<select name='round_details'>";
            	while($row = mysql_fetch_array($result)){
            		$temp .= "<option>".$row['id']. "|" . $row['round_name'] . "|" . $row['round_no'] . "</option>";
            	}
            	$temp .= "</select>";
            	echo "<form action='addCategory.php' method='post'>";
            	echo $temp;
                echo " <input type='submit' name='btn_view_round' value='View' class='btn btn-lg btn-info'/>";
                echo "</form>";
            }
        ?>

       	<div class='row'>
       		<div class='col-md-8'>
	       		<table  class="table table-bordered table-hover">
					<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
						<tr>
							<td class='lbl'>
								<label>Rounds Title</label>
							</td>
							<td>
								<input type='text' name='round_title'>
							</td>
						</tr>
						<tr>
							<td  class='lbl'><!-- this will indicate type of scoring system that will be used in a particular round-->
								<label>Scoring Type</label>
							</td>
							<td>
								<select name='scoring_type'>
									<option>Average</option>
									<option>Total</option>
								</select>
								
							</td>
						</tr>
						<tr>
							<td class="inp">
								<input type='submit' name='btn_add_round' value='Add' class="btn btn-lg btn-info">
							</td>
							<td>
								<a href="main_pageant_form.php">Cancel</a>
							</td>
						</tr>
					</form>	
				</table>
       		</div>
       	</div>
			
        </div>
	</body>
</html>
<?php
	if(isset($_POST['btn_add_round'])){
		if(!empty($_POST['round_title'])){
			
				//gets the pageant id
				$pageant_id = $sql_control -> get_pageant_id($_SESSION['event_title']);
				//passes the pageant id to the function sql->getRoundCount() to determine the current round then increments it by 1
				$round_no = $sql_control -> getRoundCount($pageant_id)+1;

				if($sql_control -> addRounds($round_no, $_POST['round_title'], $pageant_id,$_POST['scoring_type']) === true){
					header('location:addRounds.php');
				}
			
		}else{
			echo "<p style='color:red'>Compelete the Form</p>";
		}
	}
?>