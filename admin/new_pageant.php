<?php
	include '../connection/main_Connection.php';
   include '../connection/validation.php';
	$admin = new adminSession();
	$admin -> login();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
	$id_admin = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Add Pageant</title>
		<meta charset="uft-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <script type="text/javascript" src="../js/jquery2015.min.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <style type="text/css">
        	td.text, th.text{
        		text-align: center;
        		font-size: 15px;
        	}
        	input[type=text], textarea{
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
                <li><a href="adminForm.php">Home</a></li>
                <li><a href="#">Admin : <? echo $_SESSION['admin_username'] ?></a></li>
                <li><a href="add_admin.php">Add Admin</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="container">
        	<div class="row">
        		<div class="col-md-3"></div>
        		<div class="col-md-6">
        			<form onsubmit='all_()' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th colspan="2" class="text"><h3>Pageant Information</h3></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th class="text">Title:</th>
									<td><input type='text' id='title' name='title' onkeyup="title_()" required="true"/></td>
								</tr>
								
								<tr>
									<th class="text">Description:</th>
									<td>
										<textarea rows="3" cols="23" id='description' name='description' placeholder="Description of Event or Pageant here" onkeydown="description_()" required></textarea>
									</td>
								</tr>
								<tr>
									<th class="text">Date:</th>
				                    <!--
				                    <td>
				                        <select name='year'>
				                            <?php //for($i = 2015; $i < 2999; $i++)echo "<option>$i</option>";?>
				                        </select>
				                        <select name="day">
				                            <?php //for($i = 1; $i < 31; $i++ )echo "<option>$i</option>";?>
				                        </select>
				                        <select name="month">
				                            <?php //for($i = 1; $i < 13; $i++)echo "<option>$i</option>";?>
				                        </select>
				                    </td>-->
									<td><input type='text' id='date' placeholder='yyyy-dd-mm' name='date' onkeyup="date_()" required><small>(yyyy-dd-mm)</small></td>
								</tr>
								<tr>
									<th class="text">Time:</th>
									<td><input type='text' id='_time' placeholder='(eg. 19:38 or 7:38pm)' name='time' onkeyup="time_()" required><small>(eg. 19:38 or 7:38pm)</small></td>
								</tr>
								<tr>
									<th class="text">Venue:</th>
									<td><textarea rows="2" cols="23" id='venue' name='venue' placeholder="Event or Pageant venue here" onkeyup="venue_()" required></textarea></td>
								</tr>
								<tr>

									<td colspan='3' align="center"><input type='submit' value="Create" name='btn_addEvent' id='btn_addEvent' onclick="date_time()"></td>
								</tr>

								<?php
									$main = new main();
									$valid = new validation_form();
									if(isset($_POST['btn_addEvent'])){
								        //date = $_POST['year'] . '-' . $_POST['day'] . '-' . $_POST['month'];
										if(($valid -> checkDate($_POST['date']) === true) || !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['time']) && !empty($_POST['venue'])){
								            if($valid -> checkTime($_POST['time'])){
								                if($main -> add_new_pageant($_POST['title'], $_POST['description'], $_POST['date'], $_POST['time'], $_POST['venue'], $id_admin)){
													header('location:adminForm.php');
												}
											}
										}else{
											echo "<p style='color:red'>Please complete the form to proceed.</p>";
								        }
									}else{
										return false;
								    }
								?>

							</tbody>
								
						</table>
					</form>
        		</div>
        		<div class="col-md-3"></div>
        	</div>
        </div>
					
		<!-- <script type="text/javascript" src="js/date_time.js"></script> -->
	</body>
</html>