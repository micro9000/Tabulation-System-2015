<?php
		include '../connection/main_Connection.php';
		include '../connection/validation.php';
		
        //unsets the session
        
		$admin = new adminSession();
		//$admin -> sessionDestroy();
		$admin -> login();
		
		if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_password'])){
			header("location:adminForm.php");
		}
		$main = new main();
		$main -> callingAll();

		$chkTxBx = new validation_form();
?>
<html>
	<head>
		<title>Admin Login</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css" />
		<link href="../css/login.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		
		<div id='main'>
			<div id='login'>
				<h2>Admin Login</h2><br/>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						
						<label>UserName</label>
						<input type="text" name="user_name" maxlength="20" id="admin_name"><br/>
						<label>Password</label>
						<input type="password" name="ad_password" maxlength="30" id="admin_password"><br/><br/>
						<input type="submit" value="Login" name="btn_login">
						<br/><span><?php $chkTxBx -> checkTextBox();?></span>
					</form>
			</div>
		</div>
	</body>
</html>
