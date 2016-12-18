<?php
	class adminSession{
		
			
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

			//function that get the admin_id from table admin
			private function select_adminId($username){
				$connection = $this->temp_connection();//connection
				if(($this->temp_connection_check($connection)) === true){
					$this -> temp_database();//select the database
					$query = "SELECT id FROM admin WHERE username = '$username' LIMIT 1";
					$result = mysql_query($query);
					$row = mysql_fetch_array($result);
					return $row['id'];
				}
			}

			//function that check if admin username and password is exist in table admin
			//if exist return true else return false
									//get two paramiters
			private function selectAdmin($username,$password){
				
				$connection = $this->temp_connection();//connection
				
				if(($this->temp_connection_check($connection)) === true){
					$this -> temp_database();//select the database
					//select query for admin
					$selectAdminQuery = "SELECT username, password FROM admin WHERE username = '$username' AND password = '$password' LIMIT 1";
				
					$query = mysql_query($selectAdminQuery, $connection) or die(mysql_error());
					//store the number of rows
					$row = mysql_num_rows($query);
					//check if row = 1 then return true else return false
					if($row == 1){
						return true;
					}else{
						return false;
					}//end if			
				}//end if
			}//end function selectAdmin
			
			public function login(){
				session_start(); // starting session
				if(isset($_POST['btn_login'])){
					if((!empty($_POST['user_name'])) && (!empty($_POST['ad_password']))){
						$user_name = $_POST['user_name'];
						$password = $_POST['ad_password'];
						// To protect MySQL injection for security purpose
						
						$adUserName = stripslashes($user_name);
						$adPass = stripslashes($password);

						$username = mysql_real_escape_string($adUserName);
						$password = mysql_real_escape_string($adPass);
					
					
						//use the selectAdmin function to check if username and password of admin is exist in table admin
						$admin = $this-> selectAdmin($username,$password);
						
						if($admin == true){
							$_SESSION['admin_username'] = $username;
							$_SESSION['admin_password'] = $password;
								$admin_id = $this -> select_adminId($username);
							$_SESSION['admin_id'] = $admin_id;

							if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_password'])){
								header("location:index.php");
								die();
							}else{
								header("Location:adminForm.php");
								exit();
							}
						}
					}
				}//end if
			}//end function login
			
			
			//function for destroying session
			public function sessionDestroy(){
				session_start();
				session_destroy();
			}//end function sessionDestroy
            
		
	}//end class adminSession
?>
