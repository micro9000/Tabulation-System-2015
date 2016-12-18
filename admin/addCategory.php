<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
    if(!isset($_SESSION['event_id']))
        header('location:adminForm.php');
	/*if(!isset($_SESSION['event_id'],$_SESSION['round_id'])){
		header("location:adminForm.php");
	}*/
	$id_admin = $_SESSION['admin_id'];
	//echo "Admin ID: $id_admin | ";
    //echo "Event ID: " . $_SESSION['event_id'] . " | ";
    if(isset($_POST['round_details'])){
        $round_arr = explode("|", $_POST['round_details'],-1);
        $_SESSION['round_id'] = $round_arr[0];
    }
	$sql = new sql_control();
    //echo "Round ID: " . $_SESSION['round_id'];
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Add Category</title>
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
            input[type=text], select{
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
        <a href="addRounds.php">&laquo;Back</a>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <?php
                        if($sql->get_category_count($_SESSION['event_id'], $_SESSION['round_id']) > 0){
                            $categories = $sql->get_categories($_SESSION['event_id'],$_SESSION['round_id']);
                            $temp = "<select name='category_details'>";
                            while($row = mysql_fetch_array($categories))
                                $temp .= "<option>" . $row['id'] . "|" . $row['category_name'] . "|" . $row['event_id'] . "</option>";
                            $temp .= "</select>";
                            echo "<p><h3>List of Categories</h3></p>";
                            echo $temp;
                            /*
                            echo "<form method='post' action='addCriteria.php'>";
                            
                            echo "<input type='submit' name='btn_view_category' value='View' />";
                            echo "</form>";
                            */
                        }else
                            echo "<p>No categories added yet.</p>";
                            
                    ?>
                    <form action="addCategory.php" method="post">
                        <table class='table table-striped table-bordered table-hover'>
                            <tr>
                                <td align='center' colspan="2">Add Category</td>
                            </tr>
                            <tr>
                                <td>Category:</td><td><input type="text" name="category" required/></td>
                            </tr>
                            <tr>
                                <td>Max Score:</td><td><input type='text' name='max_score' required/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align='center'><input type="submit" name="btn_add_category" value="Add" /></td>
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
    if(isset($_POST['btn_add_category'])){
        //echo "Add was clicked";
        if($_POST['category']!="" && $_POST['max_score']!=""){
            if($sql->add_category($_POST['category'],$_SESSION['round_id'], $_SESSION['event_id'],$_POST['max_score'])){
                header('location:addCategory.php');
            }else{
                echo "<p style='color:red'>Category already used</p>";
            }
            
        }else{
            echo "<p style='color:red'>Please complete the form</p>";
        }
    }
?>