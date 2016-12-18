<?php
   include '../admin/connection/officialSession.php';
      $official_judge_session = new officialSession();
		$official_judge_session -> officialLogin();
		
	
	if(!isset($_SESSION['official_username']) && !isset($_SESSION['official_password']) && !isset($_SESSION['official_id'])){
		header("location:index.php");
	}
	$sql = new sql_control();
?>
<html>
	<head>
		<title>Official Form</title>
		
	</head>
	
	<body>
	   <a href='logout.php'><h2>Logout</h2></a>
		<br/>
		<table>
		   <tr>
		      <td>
		         <b>Judge name:</b>
		      </td>
		      <td>
		         <? echo $_SESSION['official_username']; ?>
		      </td>
		      <td>
		         <b>Judge Id:</b>
		      </td>
		      <td>
		          <? echo $_SESSION['official_id']; ?>
		      </td>
		   </tr>
		</table>
	</body>
</html>
