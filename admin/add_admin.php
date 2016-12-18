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
		<title>Add Admin</title>
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
        	input[type=text],input[type=password], textarea{
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
                <li><a href="new_pageant.php">Add Pageant</a></li>
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
        			<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
						<table class="table table-bordered table-hover">
							<tr>
								<td>Username</td><td><input type='text' name='user_name' maxlength='20' required></td>
							</tr>
							<tr>
								<td>Password</td><td><input type='password' name='password' maxlength='30' required></td>
							</tr>
							<tr>
								<td>Rewrite password</td><td><input type='password' name='conf_password' maxlength='30' required></td>
							</tr>
							<tr>
								<td>First Name</td><td><input type='text' name='first_name' maxlength='30' required></td>
							</tr>
							<tr>
								<td>Last Name</td><td><input type='text' name='last_name' maxlength='30' required></td>
							</tr>
							<tr>
								<td>Designation</td>
								<td>
									<select name='designation' required>
										<option></option>
										<option>admin</option>
										<option>Official</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><input type='submit' name='btn_add_admin' value='ADD'></td>
							</tr>
						</table>
					</form>
        		</div>
        		<div class="col-md-3"></div>
        	</div>
        </div>	
	</body>
</html>
<?php
	if(isset($_POST['btn_add_admin'])){
		if(!empty($_POST['user_name']) && 
			!empty($_POST['password']) && 
			!empty($_POST['conf_password']) && 
			!empty($_POST['first_name']) && 
			!empty($_POST['last_name']))
		{
			if($_POST['password'] == $_POST['conf_password']){
				$main -> add_admin($_POST['user_name'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['designation']);
			}else{
				echo "Invalid Password";
			}
		}else{
			echo "<script>alert('Complete the form')</script>";
		}
	}
?>
