<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
	$id_admin = $_SESSION['admin_id'];
	echo "Admin ID: $id_admin ";
	echo "Event id :".$_SESSION['event_id'];

	$sql_control = new sql_control();


?>
<html>
	<head>
		<title>Round Details</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript">
			function rounds(){
				document.getElementById('rd').innerHTML = "Hello";
			}
		</script>
	</head>
	<body>

		<table>
			<tr>
				<td><h3><a href='index.php'>Logout</a> | </h3></td>
				<td><h3><a href='adminForm.php'>Home</a> | </h3></td>
				<td><h3><a href='addRounds.php'>Add Round</a></h3></td>
			</tr>
		</table>
	</body>
</html>