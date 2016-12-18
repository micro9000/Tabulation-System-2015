<?php
   include '../connection/main_Connection.php';
   class officialSession{
         
         //Get the connection in class sql_control()
			private function temp_connection(){
				$sql_cont = new sql_control();
				return $sql_cont->sql_connection();
			}//end temp_connection
			
			//Get the database function in sql_control()
			private function temp_database(){
				$sql_cont = new sql_control();
				return $sql_cont->_database();
			}
			
			//return true if connection is OK else false
			private function temp_connection_check($connection){
				$sql_cont = new sql_control();
				return $sql_cont -> connection_check($connection);
			}
         
         //get official Id
         private function select_officialId($username){
				$connection = $this->temp_connection();//connection
				if(($this->temp_connection_check($connection)) === true){
					$this -> temp_database();//select the database
					$query = "SELECT id FROM official WHERE username = '$username' LIMIT 1";
					$result = mysql_query($query);
					$row = mysql_fetch_array($result);
					return $row['id'];
				}
			}
			//select official if he/she is registered as judges/offical
			//if he/she is registered return true else false
         private function selectOfficial($username, $password){
				$connection = $this -> temp_connection(); // connection
				
				if(($this -> temp_connection_check($connection)) === true){
					$this -> temp_database(); //select the database
					
					$selectOfficialQuery = "SELECT username, password FROM official WHERE username = '$username' AND password = '$password' LIMIT 1";
					
					$query = mysql_query($selectOfficialQuery, $connection) or die(msyql_error());
					//store the number of rows
					$row = mysql_num_rows($query);
					//check if row = 1 then return true else return false
					$result = ($row == 1 ? true: false);
					return $result;	
				}
			}
			
			//for judge/official login script
			//if all function return true then redirect the user to officialForm
			public function officialLogin(){
			   session_start();// start session
			   if(isset($_POST['btn_login'])){
			      if((!empty($_POST['user_name'])) && (!empty($_POST['ad_password']))){
			         $user_name = $_POST['user_name'];
						$password = $_POST['ad_password'];
						// To protect MySQL injection for security purpose
						
						$adUserName = stripslashes($user_name);
						$adPass = stripslashes($password);

						$username = mysql_real_escape_string($adUserName);
						$password = mysql_real_escape_string($adPass);
						
						$official = $this -> selectOfficial($username, $password);
						
						if($official == true){
						
							$_SESSION['official_username'] = $username;
							$_SESSION['official_password'] = $password;
							   $official_id = $this -> select_officialId($username);
							$_SESSION['official_id'] = $official_id;
							if(!isset($_SESSION['official_username']) && !isset($_SESSION['official_password'])){
								header("location:index.php");
							}else{
								header("location:main.php");
							}//end nested if four
						}//end nested if three
			      }//end nested if two
			   }//end nested if one
			}//end function officialLogin
			
   }//end officialSession class

?>
