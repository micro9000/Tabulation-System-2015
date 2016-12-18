<?php
	include '../connection/main_Connection.php';

	$admin = new adminSession();
	$admin -> login();

	$main = new main();
	
	if(!isset($_SESSION['admin_username']) && !isset( $_SESSION['admin_password'])){
		header("location:index.php");
	}
    if(!(isset($_SESSION['event_id'])))
        header('location:adminForm.php');
	/*if(!isset($_SESSION['event_title'])){
		header("location:new_pageant.php");
	}*/
	$id_admin = $_SESSION['admin_id'];
	//echo "Admin ID: $id_admin | ";
    //echo "Event ID: " . $_SESSION['event_id'];


	$sql = new sql_control();
    //$candidate_images = $sql -> mysql_fetch_array(displayCandImg($_SESSION['event_id']));
      //  echo $candidate_images['img_path'];
?>
<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<title>Upload Images</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">

		<script type="text/javascript">
			function rounds(){
				document.getElementById('rd').innerHTML = "Hello";
			}
		</script>
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

		<table align="center">
			<tr>
                <td><a href="addCandidates.php">&laquo;Back</a></td>
                <td><a href="main_pageant_form.php">Back to Pageant Details</a></td>
			</tr>
            <tr>
                <td colspan="2" align="center">
                    <h3><? echo "Event:" . $_SESSION['event_title']; ?></h3>   
                </td>
            </tr>
		</table>
         <?php
            
            //echo $sql->get_candidate_count($_SESSION['event_id']);
            
            if(!($sql->get_candidate_count_($_SESSION['event_id'])>0))
                echo 'No added candidates yet.';
            else{
                $temp = "<select name='candidate_name'>";
                $result_candidates = $sql-> get_candidates_info($_SESSION['event_id']);
                while($row=mysql_fetch_array($result_candidates)){
                    $candidate = $sql->query("SELECT * FROM candidate_info WHERE id = " . $row['id']);
                    $candidate_info = mysql_fetch_array($candidate);

                    $temp .= "<option>". $candidate_info['id']. "|" . $candidate_info['fname'] . " " . $candidate_info['lname'] ." | ".$candidate_info['gender']. "</option>";
                }
                 $temp .= "</select>";
               
            }
           
            
         ?>
         <div class="container">
             <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                     <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method="post" enctype="multipart/form-data" name="addroom">
                       <table class='table table-striped table-bordered table-hover t_cand'>
                            <tr>
                                <td>
                                    Candidate
                                </td>
                                <td><?php
                                      echo $temp;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Image
                                </td>
                                <td><input type="file" name="candidate_img" size="25"></td>
                            </tr>
                            <tr>
                                <td align="center" colspan="2">
                                    <input type="submit" value="upload" name="btn_upload">
                                </td>
                            </tr>
                            
                                  <?php
                                        if(isset($_POST['btn_upload'])){
                                            if(!empty($_FILES['candidate_img']['name']))
                                                $sql -> candidate_img_upload();
                                            else
                                                echo "<tr><td>Browse image</td></tr>";
                                        }
                                  ?>
                                
                            
                        </table>
                    </form>
                 </div>
                <div class="col-md-3"></div>
             </div>
         </div>
                    
        <script src="../js/jquery2015.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
  </html>