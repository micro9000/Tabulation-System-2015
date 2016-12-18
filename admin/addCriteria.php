<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
	if(!isset($_SESSION['event_id'],$_SESSION['round_id'])){
		header("location:adminForm.php");
	}
	$id_admin = $_SESSION['admin_id'];
	//echo "Admin ID: $id_admin | ";
    //echo "Event ID: " . $_SESSION['event_id'] . " | ";
    
    if(isset($_POST['category_details'])){
        $category_arr = explode("|", $_POST['category_details'],-1);
        $_SESSION['category_id'] = $category_arr[0];
    }
	$sql = new sql_control();
    echo "Round ID: " . $_SESSION['round_id'] . " | Category ID : " . $_SESSION['category_id'];
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Add Criteria</title>
        <meta charset="uft-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <script type="text/javascript" src="../js/jquery2015.min.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <style type="text/css">
            select{
                width: 20%;
                border:2px solid black;
                padding: 10px;
                font-size: 15px;
                cursor: pointer;
                border-radius:5px;
                margin-bottom: 15px;
            }
            ._title{
                font-size: 25px;
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
                <li><a href="new_pageant.php">Add new pageant</a></li>
                <li><a href="add_admin.php">Add Admin</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav> 
		<table>
			<tr>
				<td><h3><a href='adminForm.php'>Home</a> | </h3></td>
                <td><h3><a href='addCategory.php'>Categories</a>|</h3></td>
                <td><h3><a href='logout.php'>Logout</a></h3></td>
			</tr>
		</table>
        
        <p><b>List of Criteria</b></p>
        <?php
            if(!($sql->get_criteria_count($_SESSION['event_id'],$_SESSION['round_id'],$_SESSION['category_id']) > 0))
                echo "<p>No criteria added yet.<p>";
            else{
                $result = $sql->get_criteria($_SESSION['event_id'],$_SESSION['round_id'],$_SESSION['category_id']);
                echo "<ul>";
                while($row=mysql_fetch_array($result)){
                    echo "<li>" .$row['criteria_name'] . " - ". $row['max_score'] ."</li>";
                }
                echo "</ul>";
            }
        ?>
        
        <form action="addCriteria.php" method="post">
            <table border='1'>
                <tr>
                    <td>Criteria Name:</td><td><input type="text" name="criteria_name" /></td>
                </tr>
                <tr><td>Max Score:</td><td><input type="text" name="max_score" /></td></tr>
                <tr>
                    <td></td>
                    <td align='center'><input type="submit" name="btn_add_criteria" value="Add" /></td>
                </tr>
            </table>
        </form>
        
    </body>
  </html>
  
  <?php
    if(isset($_POST['btn_add_criteria'])){
        //echo 'btn add_criteria was clicked';
        
        if($_POST['criteria_name']!="" && $_POST['max_score']!=""){
            //echo "All are set <br/>";
            if(!($sql->checkFromTableEventIDRndIDCatID('criteria', 'criteria_name',$_POST['criteria_name'],$_SESSION['event_id'],$_SESSION['round_id'],$_SESSION['category_id']))){
                //echo "Criteria not yet in use.";
                if($sql->add_criteria($_POST['criteria_name'],$_SESSION['event_id'],$_SESSION['round_id'],$_SESSION['category_id'],$_POST['max_score']))
                    header('location:addCriteria.php');
                else
                    echo "Operation failed";   
            }
        }else
            echo "<p style='color:red'>Please complete the form.</p>";
    }
  ?>
        