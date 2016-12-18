<?php
   include '../connection/main_Connection.php';
	$admin = new adminSession();
	$admin -> login();
	unset($_SESSION['event_id'],$_SESSION['round_id'],$_SESSION['category_id']);
    $sql = new sql_control();
?>
<!DOCTYPE html>
<html lang="en">
<html>
    <head>
        <title>Events</title>
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

        <form action='main_pageant_form.php' method='post'>
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
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            </div>
          </div>
        </nav> 

        <div class="container">
<?php
	
	if(isset($_SESSION['admin_username']) && isset( $_SESSION['admin_password'])){
    	
    	echo "<p class='_title'>Event List:<p>";
        if($sql->getEventCount() > 0){
        	$result = $sql->getEvents();
        	echo "<select name='event_id'>";
        	while($row = mysql_fetch_array($result))
                echo 	"<option>". $sql ->get_pageant_id($row['title']) ."|".$row['title'] . "</option>";
        	echo "</select>";
        	echo "<input type='submit' name='btn_view_event' value='View' class='btn btn-lg btn-info'>";
         }else
            echo "There are no events yet. Do you want to <a href='new_pageant.php'>add one</a>?";
    }else
        header("location:index.php");
?>
        </form>
            <div class="row">
                <div class="col-md-12">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th colspan="7">
                                    All Pageant
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>id</td>
                                <td>Title</td>
                                <td>Description</td>
                                <td>Date</td>
                                <td>Time</td>
                                <td>Venue</td>
                                <td>Admin creator</td>
                            </tr>
                            <?
                                $result = $sql -> get_all_events();
                                while($pageant_row = mysql_fetch_array($result)) {
                                    echo "<tr>";
                                        echo "<td>".$pageant_row['id']."</td>";
                                        echo "<td>".$pageant_row['title']."</td>";
                                        echo "<td>".$pageant_row['description']."</td>";
                                        echo "<td>".$pageant_row['date']."</td>";
                                        echo "<td>".$pageant_row['time']."</td>";
                                        echo "<td>".$pageant_row['venue']."</td>";
                                        echo "<td>".$pageant_row['user_id']."</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>