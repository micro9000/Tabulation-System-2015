<?php
	
	class sql_control{
		
		//CREATE CONNECTION
		public function sql_connection(){
			//declare variable that holds the server name, user, and password
			$connection = array("server" => "localhost", "user" => "root", "password" => "");
			//create connection
			$conn = mysql_connect($connection["server"],$connection["user"], $connection["password"]);
			//return the conn
			return $conn;
		}//end sql_connection function
		
		//CHECK THE CONNECTION FUNCTION
		public function connection_check($connection){
			if(!$connection){
				die('Could not connect: '. mysql_error());
				return false;
			}else{
				return true;
			}
		}//end connection_check function
		
		//CREATE THE DATABASE progDenTabulation
		public function sql_db(){
			//Use the conn variable from sql_connection for declaring connection
			$connection = $this->sql_connection();
			$checkConn = $this->connection_check($connection);
			//check connection
			if($checkConn === true){
				// Create database progDenTabulationDB
				$createDB = "CREATE DATABASE IF NOT EXISTS progDenTabulationDB";
				mysql_query($createDB, $connection) or die(mysql_error());
			
				//Close the connection
				mysql_close($connection);
			}
		}//end sql_db function
		
		//USE DATABASE progDenTabulationiDB
		public function _database(){
			$database = mysql_select_db('progDenTabulationDB') or die(mysql_error());
			return $database;
		}
		//FUNCTION THAT CHECK TABLE IF EXISTING
		public function _table_Exists($table){
			$connection = $this->sql_connection(); // create a connection
			$checkConn = $this->connection_check($connection); // check the connection
			
			if($checkConn === true){
				$this->_database(); // use the database
				$checkTableQuery = "SHOW TABLES LIKE '".$table."'";
				$tableResult = mysql_query($checkTableQuery);
				if (mysql_num_rows($tableResult) == 1){
					return true;
				}else{
					return false;
					echo "TABLE NOT EXISTS :".die(mysql_error());
				}//end if
			}
			
		}//end function _table_Exists
		
		//FUNTION THAT CHECK THE primary key OR field FROM TABLE unknown
		//if field or primary key is existing FROM TABLE unknown
		//if the query return value one then return true
		//else return false
		//syntax checkFromTable(table name, field or column, check value)
		public function checkFromTable($table_name, $field, $value){
		
			$connection = $this->sql_connection(); // create a connection
			$checkConn = $this->connection_check($connection);//check the connection
			
			if($checkConn === true){
				$this->_database();// use the database

				//FIRST CHECK THE TABLE IF EXISTING
				if ($this->_table_Exists($table_name) == true){
					$query = "SELECT * FROM ".$table_name." WHERE ".$field." = '".$value."' LIMIT 1";
					$result = mysql_query($query, $connection) or die(mysql_error());
				
					if(mysql_num_rows($result) == 1){
						return true;
					}else{
						return false;
					}//end if
				}//end if
			}
		}//end checkFromTable function
		
		function sql_tables(){
			
			$connection = $this->sql_connection();
			$checkConn = $this->connection_check($connection);//check the connection
			if($checkConn === true){
				$this->_database();
			
				//QUERY FOR CREATING admin TABLE
				$admin_table = "CREATE TABLE IF NOT EXISTS admin(
									admin_id int NOT NULL AUTO_INCREMENT,
									user_name VARCHAR(250) NOT NULL,
									first_name VARCHAR(250) NOT NULL,
									second_name VARCHAR(250) NOT NULL,
									middle_name VARCHAR(250) NOT NULL,
									password VARCHAR(250) NOT NULL,
									PRIMARY KEY(admin_id)
								) ENGINE = INNODB";
				//CREATING TABLE admin
				mysql_query($admin_table, $connection) or die(mysql_error());
				mysql_query("ALTER TABLE admin AUTO_INCREMENT=201500");
				//END admin TABLE

				//QUERY FOR CREATING event_pageant TABLE
				$event_table = "CREATE TABLE IF NOT EXISTS event(
											event_id int NOT NULL AUTO_INCREMENT,
											title VARCHAR(255),
											description TEXT,
											e_date DATE,
											e_time TIME,
											venue VARCHAR(255),
											PRIMARY KEY(event_pageant_id),
											admin_id int NOT NULL,
											INDEX(admin_id),
											FOREIGN KEY(admin_id) REFERENCES admin(admin_id) ON UPDATE CASCADE
										)ENGINE = INNODB";
				mysql_query($event_table, $connection) or die(mysql_error());
				mysql_query("ALTER TABLE event_pageant AUTO_INCREMENT=2015000");


				$official_table = "CREATE TABLE IF NOT EXISTS official(
										official_id int NOT NULL AUTO_INCREMENT,
										username VARCHAR(255),
										password VARCHAR(255),
										alias VARCHAR(255),
										first_name VARCHAR(255),
										last_name VARCHAR(255),
										gender VARCHAR(10),
										admin_id int NOT NULL,
										event_id int NOT NULL,
										INDEX(admin_id),
										INDEX(event_id),
										FOREIGN KEY(admin_id) REFERENCES admin(admin_id) ON UPDATE CASCADE,
										FOREIGN KEY(event_id) REFERENCES event(event_id) ON UPDATE CASCADE
										)ENGINE = INNODB";
				

				//END event_pageant TABLE
			}//end if
		}//end sql_table function
		
		
		function addAdmin($user_name, $first_name, $second_name, $middle_name, $password){

			$_user_name = stripslashes($user_name);
			$_first_name = stripslashes($first_name);
			$_second_name = stripslashes($second_name);
			$_middle_name = stripslashes($middle_name);
			$_password = stripslashes($password);

			$username = mysql_real_escape_string($_user_name);
			$firstname = mysql_real_escape_string($_first_name);
			$secondname = mysql_real_escape_string($_second_name);
			$middlename = mysql_real_escape_string($_middle_name);
			$a_password = mysql_real_escape_string($_password);

			$connection = $this->sql_connection(); // create a connection

			$insert_into = "INSERT INTO admin(user_name, first_name, second_name, middle_name, password)";
			$values = "VALUES('".$username."','".$firstname."','".$secondname."','".$middlename."','".$a_password."')";
			$query = $insert_into." ".$values;
			
			if($this->checkFromTable('admin','user_name',$user_name) == false){
				mysql_query($query, $connection) or die(mysql_error());
			}else{
				echo "<script>alert('EXISTING COLUMN');</script>";
			}//END IF
		}//end addAdmin function

		function addPageant($title, $description, $date, $time, $venue,$admin_id){

			$_title = stripslashes($title);
			$_description =stripslashes($description);
			$_date = stripslashes($date);
			$_time = stripslashes($time);
			$_venue = stripslashes($venue);

			$__title = mysql_real_escape_string($_title);
			$__description = mysql_real_escape_string($_description);
			$__date = mysql_real_escape_string($_date);
			$__time = mysql_real_escape_string($_time);
			$__venue = mysql_real_escape_string($_venue);

			$connection = $this->sql_connection(); // create a connection

			$insert_into_p = "INSERT INTO event_pageant(e_title, description, e_date, e_time, venue, admin_id)";
			$values_p = "VALUES('".$title."','".$description."','".$date."','".$time."','".$venue."',".$admin_id.")";
			$query_p = $insert_into_p." ".$values_p;

			if($this->checkFromTable('event_pageant','e_title',$title) == false){
				mysql_query($query_p, $connection) or die (mysql_error());
				return true;
			}else{
				echo "<script>alert('The title of the event is already used!')</script>";
				return false;
			}//END IF
		}//end addPageant

	}//end class sql_control

?>