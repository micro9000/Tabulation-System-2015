<?php
		include '../connection/validation.php';
      $chkTxBx = new validation_form();
		include '../connection/officialSession.php';
		$official_judge_session = new officialSession();
		$official_judge_session -> officialLogin();
		
		
		
		if(isset($_SESSION['official_username']) && isset($_SESSION['official_password']) && isset($_SESSION['official_id'])){
		   header("location:home.php");
		}
?>
<html>
	<head>
		<title>Official Login</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css" />
		<!--<link href="../css/login.css" rel="stylesheet" type="text/css" />-->
	</head>
	<body>
		<br/><br/><br/>
		<div id='main'>
			<div id='login'>
				<h2>Official Login</h2><br/>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<label>UserName</label>
						<input type="text" name="user_name" maxlength="50" id="admin_name"><br/>
						<label>Password</label>
						<input type="password" name="ad_password" maxlength="50" id="admin_password" placeholder="* * * * * * * * * * *"><br/><br/>
						<input type="submit" value="Login" name="btn_login">
						<br/><span><?php $chkTxBx -> checkTextBox();?></span>
					</form>
			</div>
		</div>

	</body>
</html>
