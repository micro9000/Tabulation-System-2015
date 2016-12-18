<?php
			
	include '../connection/main_Connection.php';
	$admin = new adminSession();
	$admin -> login();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
	$main = new main();
?>

<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Add Judge</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
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
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav>
        
        <div class="container">
        	<div class="row">
        		<a href="main_pageant_form.php">&laquo;Back</a>
	        	<h3>Add Official</h3>
        		<div class="col-md-6">
        			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
						<table class='table table-striped table-bordered table-hover'>
							<!--<tr>
								<td>Username</td><td><input type='text' name='user_name' maxlength='20' required></td>
							</tr>
							-->
			                <tr>
			                    <td>Alias</td><td><input type="text" name="alias" required=""/></td>
			                </tr>
							<tr>
								<td>First Name</td><td><input type='text' name='first_name' maxlength='30' required></td>
							</tr>
							<tr>
								<td>Last Name</td><td><input type='text' name='last_name' maxlength='30' required></td>
							</tr>
			                <tr>
			                    <td>Gender</td>
			                    <td>
			                        <select name="gender">
			                            <option>Male</option>
			                            <option>Female</option>
			                        </select>
			                    </td>
			                </tr>
			                 <tr>
								<td>Password</td><td><input type='password' name='password' maxlength='30' required></td>
							</tr>
			                <tr>
								<td>Confirm Password</td><td><input type='password' name='conf_password' maxlength='30' required></td>
							</tr>
							<tr>
								<td>Designation</td>
								<td>
									<select name='designation' required>
										<option>official</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><input type='submit' name='btn_add_admin' value='ADD'></td>
							</tr>
						</table>
					</form>
        		</div>
        		<div class="col-md-6">
        			<ul>
			            <?php
			                $sql = new sql_control();
			                $result = $sql->get_officials($_SESSION['event_id']);
			                while($row = mysql_fetch_array($result)){
			                    echo "<li><i>".$row['fname'] . " " . $row['lname'] . "</i> : <b> " . $row['username'] . "</b>";
			                }
			            ?>
			        </ul>
        		</div>
        	</div>
	        	
        </div>
		
		<script src="../js/jquery2015.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
	</body>
</html>

<?php
	if(isset($_POST['btn_add_admin'])){
		if(/*!empty($_POST['user_name']) &&*/ 
			!empty($_POST['password']) && 
			!empty($_POST['conf_password']) && 
			!empty($_POST['first_name']) && 
			!empty($_POST['last_name']) &&
            !empty($_POST['gender']) && 
            !empty($_POST['alias']))
		{
			if($_POST['password'] == $_POST['conf_password']){
				//call the function for adding official
                //$sql = new sql_control();
                //echo $sql->check_official($_POST['user_name'],$_SESSION['event_id']);
                $result = $sql->add_official($_POST['alias'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'],$_POST['password'],$_POST['designation'],$_SESSION['admin_id'],$_SESSION['event_id']);
                if(!($result))
                    echo "<p style='color:red'>Operation failed.</p>";
                else{
                    echo "Operation success.";
                    header('location:add_judge.php');
                }
            }else{
				echo "Invalid Password";
			}
		}else{
			echo "<p style='color:red'>Please complete the form.</p>";
		}
	}
?>
