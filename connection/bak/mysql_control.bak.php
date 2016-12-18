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
	/*
		public function sql_connection($server, $dbuser, $dbpassword){
			$con_string = array("server"=>$server, "user"=>$dbuser,"password"=>$dbpassword);
 
        	$connection = mysql_connect($con_string["server"], $con_string["user"], $con_string['password']);
        
        	return $connection;
		}
	*/
		//CHECK THE CONNECTION FUNCTION
		public function connection_check($connection){
			if(!$connection){
				die('Could not connect: '. mysql_error());
				return false;
			}else{
				return true;
			}
		}//end connection_check function

		
		//USE DATABASE progDenTabulationiDB
		public function _database(){
			$database = mysql_select_db('prog_den_tabulation') or die(mysql_error());
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
					echo "TABLE NOT EXISTS :".die(mysql_error() . "Line 54");
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
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 76");
				
					if(mysql_num_rows($result) == 1){
						return true;
					}else{
						return false;
					}//end if
				}//end if
			}
		}//end checkFromTable function
		
		
		function addAdmin($user_name, $password, $fname, $lname, $designation){

			$_user_name = stripslashes($user_name);
			$_first_name = stripslashes($password);
			$_second_name = stripslashes($fname);
			$_middle_name = stripslashes($lname);
			$_password = stripslashes($designation);

			$username = mysql_real_escape_string($_user_name);
			$firstname = mysql_real_escape_string($_first_name);
			$secondname = mysql_real_escape_string($_second_name);
			$middlename = mysql_real_escape_string($_middle_name);
			$a_password = mysql_real_escape_string($_password);

			$connection = $this->sql_connection(); // create a connection

			$insert_into = "INSERT INTO admin(username, password, fname, lname, designation)";
			$values = "VALUES('".$username."','".$password."','".$fname."','".$lname."','".$designation."')";
			$query = $insert_into." ".$values;
			
			if($this->checkFromTable('admin','username',$username) == false){
				mysql_query($query, $connection) or die(mysql_error() . "Line 109");
			}else{
				echo "<script>alert('EXISTING COLUMN');</script>";
			}//END IF
		}//end addAdmin function

		function addEvent($title, $description, $date, $time, $venue,$admin_id){

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

			$insert_into_p = "INSERT INTO event(title, description, date, time, venue, user_id)";
			$values_p = "VALUES('".$title."','".$description."','".$date."','".$time."','".$venue."',".$admin_id.")";
			$query_p = $insert_into_p." ".$values_p;

			if($this->checkFromTable('event','title',$title) == false){
				mysql_query($query_p, $connection) or die (mysql_error() . "Line 136");
				return true;
			}else{
				echo "<script>alert('The title of the event is already used!')</script>";
				return false;
			}//END IF
		}//end addPageant

		function addRounds($roundNum, $roundTitle, $event_id ,$roundPercent){
			$round_no = stripslashes($roundNum);
			$round_title = stripslashes($roundTitle);
			$round_percent = stripslashes($roundPercent);

			$_round_no = mysql_real_escape_string($round_no);
			$_round_title = mysql_real_escape_string($roundTitle);
			$_round_percent = mysql_real_escape_string($round_percent);

			$connection = $this -> sql_connection();// create a conenction

			$insert_into_rounds = "INSERT INTO round(round_no, round_name, event_id, percentage)";
			$values_rounds = "VALUES(".$_round_no.",'".$_round_title."',".$event_id.",".$_round_percent.")";

			$query = $insert_into_rounds." ".$values_rounds;

			if($this->checkFromTableEventID('round', 'round_name', $_round_title,$event_id) == false){
				mysql_query($query, $connection) or die (mysql_error() . "Line 161");
				return true;
			}else{
				echo "<script>alert('Round Title already used')</script>";
				return false;
			}

		}
		//function that get the event_pageant_id from table event
			/*public function select_pageantId($event_title){
				$connection = $this->sql_connection(); // create a connection
				if(($this -> connection_check($connection)) === true){
					$this -> _database();//select the database
					$query = "SELECT id FROM event WHERE title ='".$event_title."' LIMIT 1";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 175");
					$row = mysql_fetch_array($result);
					return $row['id'];
				}
			}*/
            
        function get_pageant_id($event_title){
            $connection = $this->sql_connection(); // create a connection
            if(($this -> connection_check($connection)) === true){
                $this -> _database();//select the database
				$query = "SELECT id FROM event WHERE title ='".$event_title."' LIMIT 1";
				$result = mysql_query($query, $connection) or die(mysql_error() . "Line 175");
				$row = mysql_fetch_array($result);
                return $row['id'];
			}
        }
		function getRoundCount($event_id){
			$connection = $this->sql_connection(); // create a connection

			if(($this -> connection_check($connection) === true)){
				$this -> _database();//select the database
					$query = "SELECT COUNT(round_no) AS count FROM round WHERE event_id = $event_id";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 186");
					$count = mysql_fetch_array($result);
					return $count['count'];
			}
		}
		
		function getRounds($event_id){
			$connection = $this->sql_connection(); // create a connection

			if(($this -> connection_check($connection) === true)){
				$this -> _database();//select the database
					$query = "SELECT * FROM round WHERE event_id = $event_id";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 208");
					return $result;
			}
		}

		function getEvents(){
			$connection = $this->sql_connection();
			if(($this -> connection_check($connection) === true)){
				$this -> _database();//select the database
					$query = "SELECT * FROM event";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 16");
					
					return $result;
			}
		}
        
        
        function getEventCount(){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT COUNT(id) AS id FROM event";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 229");
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        
        function add_category($title, $round_id, $event_id){
            
			$title = stripslashes($title);

			$title = mysql_real_escape_string($title);

			$connection = $this -> sql_connection();// create a conenction

			$insert_into_category = "INSERT INTO category(category_name,event_id,round_id)";
			$values_category = "VALUES('".$title."',".$event_id.",".$round_id.")";

			$query = $insert_into_category." ".$values_category;

			if(!($this->checkFromTableEventID('category', 'category_name', $title, $event_id))){
				mysql_query($query, $connection) or die (mysql_error() . "Line 250");
				return true;
			}else
				return false;
			
        }
         //checks if it exists in the table
        public function checkFromTableEventID($table_name, $field, $value,$event_id){
		
			$connection = $this->sql_connection(); // create a connection
			$checkConn = $this->connection_check($connection);//check the connection
			
			if($checkConn === true){
				$this->_database();// use the database

				//FIRST CHECK THE TABLE IF EXISTING
				if ($this->_table_Exists($table_name) == true){
					$query = "SELECT * FROM ".$table_name." WHERE ".$field." = '".$value."' AND event_id = ".$event_id ." LIMIT 1";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 76");
				
					if(mysql_num_rows($result) == 1){
						return true;
					}else{
						return false;
					}//end if
				}//end if
			}
		}//end checkFromTable function
        public function get_category_count($event_id,$round_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT COUNT(id) AS id FROM category WHERE round_id = $round_id AND event_id = $event_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 229");
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        
        //returns the result of query in table category
        public function get_categories($event_id,$round_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT * FROM category WHERE round_id = $round_id AND event_id = $event_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 229");
                
                return $result;
            }
        }
        
        public function get_category_id($event_id, $round_id, $category_title){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT * FROM category WHERE round_id = $round_id AND event_id = $event_id AND category_name = $category_title";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 306");
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        //gets the current count of criteria in the database
        public function get_criteria_count($event_id, $round_id, $category_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT COUNT(id) AS id FROM criteria WHERE round_id = $round_id AND event_id = $event_id AND category_id = $category_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 317");
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        
        //adds criteria to the database
        public function add_criteria($criteria_name, $event_id, $round_id, $category_id, $max_score){
            
			$criteria_name = stripslashes($criteria_name);

			$connection = $this -> sql_connection();// create a conenction

			$insert_into_criteria = "INSERT INTO criteria(criteria_name,event_id,round_id,category_id,max_score)";
			$values_criteria = "VALUES('$criteria_name',$event_id,$round_id,$category_id,$max_score)";

			$query = $insert_into_criteria." ".$values_criteria;

			if(!($this->checkFromTableEventID('criteria', 'criteria_name', $criteria_name, $event_id))){
				mysql_query($query, $connection) or die (mysql_error() . "Line 328");
				return true;
			}else
				return false;
        }
        
        //checks if it exists in the table
        public function checkFromTableEventIDRndIDCatID($table_name, $field, $value,$event_id,$round_id,$category_id){
		
			$connection = $this->sql_connection(); // create a connection
			$checkConn = $this->connection_check($connection);//check the connection
			
			if($checkConn === true){
				$this->_database();// use the database

				//FIRST CHECK THE TABLE IF EXISTING
				if ($this->_table_Exists($table_name) == true){
					$query = "SELECT * FROM $table_name WHERE $field = '$value' AND event_id = $event_id AND round_id = $round_id AND category_id = $category_id LIMIT 1";
					$result = mysql_query($query, $connection) or die(mysql_error() . "Line 354");
				    
					if(mysql_num_rows($result) == 1){
						return true;
					}else{
						return false;
					}//end if
				}//end if
			}
		}//end checkFromTable function
        
        public function get_criteria($event_id, $round_id, $category_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT * FROM criteria WHERE round_id = $round_id AND event_id = $event_id AND category_id = $category_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 370");
                
                return $result;
            }
        }
        
        //gets the current count of candidates given the event id
        public function get_candidate_count($event_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT COUNT(candidate_id) AS id FROM candidate WHERE event_id = $event_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 382");
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        
        public function get_candidates($event_id){
        	$connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $result_candidates = $this->query("SELECT * FROM candidate WHERE event_id = " . $_SESSION['event_id']);
                return $result_candidates;
            }
        }

        public function query($query_string){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $result = mysql_query($query_string, $connection) or die(mysql_error() . "Line 392");
                return $result;
            }
        }
        
        public function uploadImgIntoTable($path, $img_name ,$candidate_id, $event_id){
				$connection = $this -> sql_connection(); //connection

				if(($this->connection_check($connection)) == true){
					$this -> _database();

					$uploadImgQuery = "INSERT INTO images(img_path, img_name, candidate_id, event_id) VALUES('$path', '$img_name', $candidate_id, $event_id)";
					$result = mysql_query($uploadImgQuery) or die(mysql_error());
					return true;
				}else{
					return false;	
				}
		}

		public function candidate_img_upload(){ 
	            //initialize message variable to none
	            $message = "";
	            //initialize valie_file variable to true by default
	            $valid_file = true;

	            //Target Directory to copy the upload image
	            $target_dir = "../images/uploads/" . $_SESSION['event_folder']."/";
	            //get the name of the file to upload
	            $target_file = $target_dir . basename(@$_FILES['candidate_img']['name']);
	            //get the file extension
	            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

	                //Check if image file is a actual image or fake image
	                $check = getimagesize($_FILES['candidate_img']['tmp_name']);
	                if($check !== false){
	                    //echo "File is an image - ". $check["mime"] . ".";
	                    $valid_file = true;
	                }else{
	                    //echo "File is not an image.";
	                    $valid_file = false;
	                }
	                // Check if file already Exists
	                if(file_exists($target_file)){
	                    echo "Picture already exists";
	                    $valid_file = false;
	                }
	                //check the image size
	                if($_FILES['candidate_img']['size'] > 1024000){
	                    echo "Image is too large.";
	                    $valid_file = false;
	                }

	                //Check the file exetension is valid
	                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
	                    echo "Only JPG, JPEG, PNG & GIF files are allowed.";
	                    $valid_file = false;
	                }

	                //Check if $valid_file is false
	                if($valid_file === false){
	                    echo "Sorry, your file was not uploaded.";
	                }else{
	                    if (!isset($_FILES['candidate_img']['tmp_name'])) {
	                        echo "False";
	                    
	                    }else{
	                        $file=$_FILES['candidate_img']['tmp_name'];
	                        $image= addslashes(file_get_contents($_FILES['candidate_img']['tmp_name']));
	                        $image_name= addslashes($_FILES['candidate_img']['name']);
	                            
	                        move_uploaded_file($_FILES["candidate_img"]["tmp_name"], $target_dir. $_FILES["candidate_img"]["name"]);

				            $_candidate = $_POST['candidate_name'];
				            $cand_no = split('[|]', $_candidate);

    						if($this->uploadImgIntoTable($target_dir, $image_name ,$cand_no[0], $_SESSION['event_id']) === true ){
    							header('location:upload_img.php');
    						}
	                    }//end if candidate_img isset
	                }//end if check valid_file is === false

	    }//end function candidate_img_upload

	    public function displayCandidateImg($event_id, $official_id ){
	    	
	    }


        public function add_candidate($candidate_no,$fname, $lname,$mname, $age, $gender, $height,$vitals, $address,$event_id,$round_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "INSERT INTO candidate_info(fname,lname,mname,age,gender,height,vitals, address,event_id)".
                            "VALUES('$fname','$lname','$mname',$age,'$gender', $height,'$vitals', '$address',$event_id)";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 402");
                
                if($result){
                    
                    $query = "SELECT id FROM candidate_info WHERE fname = '$fname' AND mname = '$mname' AND lname='$lname' AND event_id = $event_id";
                    $result = mysql_query($query);
                    $fetch_new_data = mysql_fetch_array($result);
                    $candidate_id = $fetch_new_data['id'];
                    $query_2 = "INSERT INTO candidate(candidate_id,candidate_no,event_id,round_id)".
                                "VALUES($candidate_id, $candidate_no, $event_id,$round_id)";
                    echo $query_2;
                    $result2 = mysql_query($query_2);
                    if($result2){
                        echo "Sucessfuly added the candidate.";
                        return true;   
                    }
                    else{
                        echo "Unsuccessful operation.";
                        return false;
                    }
                }else{
                    return false;
                }//end if
            }
        }
        
        //function for adding official
        public function add_official($username, $alias, $fname, $lname, $gender, $password, $designation,$admin_creator_id, $event_id){
            if(!($this->check_official($username,$event_id))){
                //echo "Not yet in db";
                //username is $fname_$lname-$event_id-$admin-creator_id
                $username = $fname."_".$lname."_".$event_id."_".$admin_creator_id;
                $result = $this->query("INSERT INTO official(username, password, alias, fname, lname, gender, designation,admin_creator_id, event_id)
                                VALUES('$username', '$password', '$alias', '$fname', '$lname', '$gender', '$designation', $admin_creator_id, $event_id)");
                return $result;
            }else
                echo "<p style='color:red'>Already in the exists.</p>";
        }
        
        //checks if the official is already existing in the table
        public function check_official($username, $event_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                
                $query_str = "SELECT COUNT(id) AS id FROM official WHERE username = '$username' AND event_id = $event_id";
                $result = mysql_query($query_str);
                $row = mysql_fetch_array($result);
                return $row['id'];
            }
        }
        
        public function get_officials($event_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "SELECT * FROM official WHERE event_id = $event_id";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 458");
                
                return $result;
            }
        }

        //this will return the event id designated for the official
        public function get_event_id_for_official($username){
        	$connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                
                $result = $this->query("SELECT * FROM official WHERE username = '$username'");
                $row = mysql_fetch_array($result);
                return $row['event_id'];
            }
        }

        public function get_event($event_id){
        	$connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                
                $result = $this->query("SELECT * FROM event WHERE id = $event_id");
                return $result;
            }
        }


	}//end class sql_control
    


?>
