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
	echo "Admin ID: $id_admin | ";
    echo "Event ID: " . $_SESSION['event_id'];
	$sql = new sql_control();


?>
<html>
	<head>
		<title>Add Candidates</title>
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
				<td><h3><a href='adminForm.php'>Home</a> | </h3></td>
                <td><h3><a href="main_pageant_form.php">Pageant Details</a> | </h3></td>
                <td><h3><a href='upload_img.php'>Upload Images</a> | </h3></td>
                <td><h3><a href='logout.php'>Logout</a> | </h3></td>
			</tr>
		</table>
         <?php
            echo "Event: <b>" . $_SESSION['event_title'] ."</b></br>";
            //echo $sql->get_candidate_count($_SESSION['event_id']);
            echo "<h4>Candidates</h4>";
            if(!($sql->get_candidate_count($_SESSION['event_id'])>0))
                echo 'No added candidates yet.';
            else{
                $temp = "<ul>";
                $result_candidates = $sql->query("SELECT * FROM candidate WHERE event_id = " . $_SESSION['event_id']);
                while($row=mysql_fetch_array($result_candidates)){
                    $candidate = $sql->query("SELECT * FROM candidate_info WHERE id = " . $row['candidate_id']);
                    $candidate_info = mysql_fetch_array($candidate);
                    $temp .= "<li>Name: " . $candidate_info['fname'] . " " . $candidate_info['lname'] . "</li>";
                }
                 $temp .= "</ul>";
                 echo $temp;
            }
           
            
         ?>
        <form action="addCandidates.php" method="post" enctype="multipart/form-data" name="addroom">
            <table border='1'>
                <!-- temporary -->
                <tr><td>Round id</td><td><input type="text" name="round_id" /></td></tr>
                <!-- temporary -->
                <tr><td>Candidate No:</td><td><input type="text" name="candidate_no" /></td></tr>
                <tr>
                    <td>First Name:</td><td><input type="text" name="fname" /></td>
                </tr>
                <tr><td>Last Name:</td><td><input type="text" name="lname" /></td></tr>
                <tr><td>Middle Name:</td><td><input type="text" name="mname" /></td></tr>
                <tr>
                    <td>Age</td>
                    <td>
                        <select name="age">
                            <?php
                                for($i = 15; $i < 35; $i++){
                                    echo "<option>$i</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <select name="gender">
                            <option>Female</option>
                            <option>Male</option>
                            <option>Other</option>
                        </select>
                    </td>
                </tr>
                <tr><td>Height:</td><td><input type="text" name="height" /></td></tr>
                <tr><td>Vitals:</td><td><input type="text" name="vitals" /></td></tr>
                <tr><td>From:</td><td><input type="text" name="from" /></td></tr>
                <tr><td>Picture:</td><td><input type="file" name="image" class="ed"></td></tr>
                <tr>
                    <td></td>
                    <td align='center'><input type="submit" name="btn_add_candidate" value="Add" /></td>
                </tr>
            </table>
        </form>
        
    </body>
  </html>
  
  <?php
    if(isset($_POST['btn_add_candidate'])){
        //echo 'btn add_criteria was clicked';
        
        if($_POST['candidate_no']!="" && $_POST['fname']!="" && $_POST['lname']!=""  && $_POST['mname']!=""  && $_POST['gender']!=""  && $_POST['height']!=""  && $_POST['vitals']!=""  && $_POST['from']!=""){
            //echo "Forms were complete";

            $result = $sql->query("SELECT COUNT(id) as id FROM candidate_info WHERE fname = '" . $_POST['fname'] . "' AND " . 
                        "lname = '" . $_POST['lname'] . "' AND mname = '" . $_POST['mname'] . "' AND event_id = " . $_SESSION['event_id']);

            $row = mysql_fetch_array($result);


            if(($row['id']==0)){
                echo "Not yet in table";
                $sql->add_candidate($_POST['candidate_no'],$_POST['fname'], $_POST['lname'],$_POST['mname'],$_POST['age'],$_POST['gender'],$_POST['height'],$_POST['vitals'],$_POST['from'],$_SESSION['event_id'],$_POST['round_id']);
                header('location:addCandidates.php');
            }   
            else
                echo "Already exists in the table.";
                
        }else
            echo "<p style='color:red'>Please complete the form.</p>";
    }
  ?>