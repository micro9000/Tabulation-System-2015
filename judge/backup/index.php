<?php
		include '../admin/connection/validation.php';
      $chkTxBx = new validation_form();
		include '../admin/connection/officialSession.php';
		$official_judge_session = new officialSession();
		$official_judge_session -> officialLogin();
		
		
		
		if(isset($_SESSION['official_username']) && isset($_SESSION['official_password']) && isset($_SESSION['official_id'])){
		   header("location:officialForm.php");
		}
?>
<html>
	<head>
		<title>Admin Login</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id='main'>
			<h1>Programmers' Den Tabulation</h1>
			<div id='login'>
				<h2>Login Form</h2><br/>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						
						<label>UserName</label>
						<input type="text" name="user_name" maxlength="20" id="admin_name"><br/>
						<label>Password</label>
						<input type="password" name="ad_password" maxlength="30" id="admin_password"><br/>
						<label>Designation</label>
						<select>
							<option>admin</option>
							<option>official</option>
						</select>
						<input type="submit" value="Login" name="btn_login">
						<br/><span><?php $chkTxBx -> checkTextBox();?></span>
					</form>
			</div>
		</div>
	</body>
</html>
