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
		
		//query function for all SELECT query here in class sql_control
		public function query($query_string){
			//echo $query_string;
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $result = mysql_query($query_string, $connection) or die(mysql_error() . "Line 83");
                return $result;
            }
        }

		function addAdmin($user_name, $password, $fname, $lname, $designation){

			$_user_name = stripslashes($user_name);
			$_password = stripslashes($password);
			$_f_name = stripslashes($fname);
			$_l_name = stripslashes($lname);
			$_designation = stripslashes($designation);

			$username_ = mysql_real_escape_string($_user_name);
			$password_ = mysql_real_escape_string($_password);
			$firstname_ = mysql_real_escape_string($_f_name);
			$lastname_ = mysql_real_escape_string($_l_name);
			$designation_ = mysql_real_escape_string($_designation);

			$connection = $this->sql_connection(); // create a connection

			$insert_into = "INSERT INTO admin(username, password, fname, lname, designation)";
			$values = "VALUES('".$username_."','".$password_."','".$firstname_."','".$lastname_."','".$designation_."')";
			$query = $insert_into." ".$values;
			
			if($this->checkFromTable('admin','username',$username_) == false){
				mysql_query($query, $connection) or die(mysql_error() . "Line 110");
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

		function addRounds($roundNum, $roundTitle, $event_id,$scoring_type){
			$round_no = stripslashes($roundNum);
			$round_title = stripslashes($roundTitle);
			$round_percent = stripslashes($roundPercent);

			$_round_no = mysql_real_escape_string($round_no);
			$_round_title = mysql_real_escape_string($roundTitle);

			$connection = $this -> sql_connection();// create a conenction

			$insert_into_rounds = "INSERT INTO round(round_no, round_name, event_id,scoring_type)";
			$values_rounds = "VALUES($_round_no,'".$_round_title."',$event_id,'".$scoring_type."')";
			
			$query = $insert_into_rounds." ".$values_rounds;
			if($this->checkFromTableEventID('round', 'round_name', $_round_title,$event_id) == false){
				mysql_query($query, $connection) or die (mysql_error() . "Line 161");
				return true;
			}else{
				echo "<script>alert('Round Title already used')</script>";
				return false;
			}

		}

         
        function get_pageant_id($event_title){
			$result = $this -> query("SELECT id FROM event WHERE title ='".$event_title."' LIMIT 1");
			$row = mysql_fetch_array($result);
			return $row['id'];
        }
		function getRoundCount($event_id){
			$result = $this -> query("SELECT COUNT(round_no) AS count FROM round WHERE event_id = $event_id");
			$count = mysql_fetch_array($result);
			return $count['count'];
		}
		
		function getRounds($event_id){
			$result = $this -> query("SELECT * FROM round WHERE event_id = $event_id");
			return $result;
		}

		function getEvents(){
			$result = $this -> query("SELECT * FROM event");
			return $result;
		}
        
        
        function getEventCount(){
            $result = $this -> query("SELECT COUNT(id) AS id FROM event");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        
        function check_candidate_score($category_id, $candidate_id, $event_id, $scorer_id, $round_id){
        	$result = $this -> query("SELECT * FROM category_score WHERE category_id = $category_id AND candidate_id = $candidate_id AND  event_id = $event_id AND scorer_id = $scorer_id AND round_id = $round_id");
        	return $result;
        }

        function get_all_rounds_event($event_id){
        	$result = $this -> query("SELECT * FROM round WHERE event_id = $event_id");
        	return $result;
        }
        function add_category($title, $round_id, $event_id,$max_score){
            
			$title = stripslashes($title);
			$max_score_ = stripslashes($max_score);

			$title = mysql_real_escape_string($title);
			$_max_score = mysql_real_escape_string($max_score_);

			$connection = $this -> sql_connection();// create a conenction

			$insert_into_category = "INSERT INTO category(category_name,event_id,round_id,max_score)";
			$values_category = "VALUES('$title',$event_id,$round_id,$_max_score)";

			$query = $insert_into_category." ".$values_category;

			if(!($this->checkFromTableEventID('category', 'category_name', $title, $event_id))){
				mysql_query($query, $connection) or die (mysql_error() . "Line 250");
				return true;
			}else
				return false;
			
        }
         //checks if it exists in the table
        public function checkFromTableEventID($table_name, $field, $value,$event_id){
				$result = $this -> query("SELECT * FROM ".$table_name." WHERE ".$field." = '".$value."' AND event_id = ".$event_id ." LIMIT 1");
				if(mysql_num_rows($result) == 1)
					return true;
				else
					return false;
			
		}//end checkFromTable function
		
        public function get_category_count($event_id,$round_id){
            $result = $this -> query("SELECT COUNT(id) AS id FROM category WHERE round_id = $round_id AND event_id = $event_id");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        
        //returns the result of query in table category
        public function get_categories($event_id,$round_id){
            $query_str ="SELECT * FROM category WHERE event_id = $event_id AND round_id = $round_id";
            $result = $this -> query($query_str);
            return $result;
        }
        
		//gets the category info
		public function get_category_info($category_id){
			$result = $this->query("SELECT * FROM category WHERE id = $category_id");
			$row = mysql_fetch_array($result);
			return $row;
		}
        public function get_category_id($event_id, $round_id, $category_title){
            $result = $this -> query("SELECT * FROM category WHERE round_id = $round_id AND event_id = $event_id AND category_name = $category_title");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        //gets the current count of criteria in the database
        public function get_criteria_count($event_id, $round_id, $category_id){
            $result = $this -> query("SELECT COUNT(id) AS id FROM criteria WHERE round_id = $round_id AND event_id = $event_id AND category_id = $category_id");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
		/*
        //get the round_name
        public function get_rounds($event_id){
        	$result = $this -> query("SELECT round_name FROM round WHERE event_id = $event_id");
        	return $result;
        }*/
        //get the round_name
        public function get_rounds($event_id){
        	$result = $this -> query("SELECT * FROM round WHERE event_id = $event_id");
        	return $result;
        }
        
        public function get_first_round($event_id){
        	$result = $this -> query("SELECT id FROM round WHERE event_id = $event_id LIMIT 1");
        	$row = mysql_fetch_array($result);
        	return $row['id'];
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
			$result = $this -> query("SELECT * FROM $table_name WHERE $field = '$value' AND event_id = $event_id AND round_id = $round_id AND category_id = $category_id LIMIT 1");
			if(mysql_num_rows($result) == 1)
				return true;
			else
				return false;
		}//end checkFromTable function
        
        public function get_criteria($event_id, $round_num, $category_id){
            $result = $this -> query("SELECT * FROM criteria WHERE round_id = $round_num AND event_id = $event_id AND category_id = $category_id");
            return $result;
        }

        
        //gets the current count of candidates given the event id
        public function get_candidate_count($event_id, $gender){
            $result = $this -> query("SELECT COUNT(id) AS id FROM candidate_info WHERE event_id = $event_id AND gender = '$gender'");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        
        public function get_candidate_count_($event_id){
            $result = $this -> query("SELECT COUNT(id) AS id FROM candidate_info WHERE event_id = $event_id");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        public function get_candidate_count_no($event_id){
            $result = $this -> query("SELECT COUNT(id) AS id FROM candidate_info WHERE event_id = $event_id");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
		
		
		//gets the info of the candidate
		public function get_candidate_info($candidate_id){
			$result = $this->query("SELECT * FROM candidate_info WHERE id = $candidate_id");
			$row = mysql_fetch_array($result);
			return $row;
		}
		
        public function get_male_candidates_info($event_id){
        	$male_result = $this -> query("SELECT * FROM candidate_info WHERE gender ='Male' AND event_id = $event_id");
        	return $male_result;
        }
        public function get_female_candidates_info($event_id){
        	$female_result = $this -> query("SELECT * FROM candidate_info WHERE gender ='Female' AND event_id = $event_id");
        	return $female_result;
        }
        public function get_male_candidates_no($event_id, $cand_id){
        	$male_no = $this -> query("SELECT candidate_no FROM candidate WHERE event_id = $event_id AND candidate_id = $cand_id");
        	return $male_no;
        }
        public function get_female_candidates_no($event_id, $cand_id){
        	$female_no = $this -> query("SELECT candidate_no FROM candidate WHERE event_id = $event_id AND candidate_id = $cand_id");
        	return $female_no;
        }
        
        public function get_candidates_info($event_id){
        	$select_All_can = $this -> query("SELECT * FROM candidate_info WHERE event_id = $event_id");
        	return $select_All_can;
        	
        }
        public function get_candidate_img($event_id, $candidate_id){
        	$m_query = $this -> query("SELECT * FROM images WHERE event_id = $event_id AND candidate_id = $candidate_id");
        	return $m_query;
        }

        public function insert_candidate_score($category_id, $candidate_id, $event_id, $scorer_id, $round_id, $score,$redirect_page_lin){
        	$connection = $this -> sql_connection(); //connection

        	if($this -> connection_check($connection) == true){
        		$this -> _database();

        		$insertScoreQuery = "INSERT INTO category_score(category_id, candidate_id, event_id, round_id, scorer_id, score) VALUES($category_id, $candidate_id, $event_id, $round_id, $scorer_id, $score)";
        		//echo $insertScoreQuery;
        		mysql_query($insertScoreQuery, $connection) or die("Line 347: ".mysql_error());
        		//return true;
        		echo "<script>window.open('$redirect_page_lin','_self')</script>";
        	}else{
        		//return false;
        		echo "<script>window.open('$redirect_page_lin','_self')</script>";
        	}
			
        }
        public function uploadImgIntoTable($path, $img_name ,$candidate_id, $event_id){
				$connection = $this -> sql_connection(); //connection

				if(($this->connection_check($connection)) == true){
					$this -> _database();

					$uploadImgQuery = "INSERT INTO images(img_path, img_name, candidate_id, event_id) VALUES('$path', '$img_name', $candidate_id, $event_id)";
					mysql_query($uploadImgQuery) or die(mysql_error());
					return true;
				}else{
					return false;	
				}
		}

		public function candidate_img_upload(){ 

			echo "<tr><td colspan='3' style='color:red; text-align:center;'>";
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
	                    echo " Picture already exists ";
	                    $valid_file = false;
	                }
	                //check the image size
	                if($_FILES['candidate_img']['size'] > 1024000){
	                    echo " Image is too large. ";
	                    $valid_file = false;
	                }

	                //Check the file exetension is valid
	                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
	                    echo " Only JPG, JPEG, PNG & GIF files are allowed. ";
	                    $valid_file = false;
	                }

	                //Check if $valid_file is false
	                if($valid_file === false){
	                    echo " Sorry, your file was not uploaded. ";
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

				            echo " Candidate id: ". $cand_no[0]."<br/>";
	                        echo " Path: ".$target_dir ."<br/>" ;
	                        echo " Image name: ".$image_name ."<br/>";
    						echo " Event ID: " . $_SESSION['event_id']."<br/>";
    						
    						if($this->uploadImgIntoTable($target_dir, $image_name ,$cand_no[0], $_SESSION['event_id']) === true ){
    							//header('location:upload_img.php');
    							echo "Sucessfuly upload";
    						}
    						
	                    }//end if candidate_img isset
	                }//end if check valid_file is === false
	         echo "</td></tr>";
	    }//end function candidate_img_upload

        public function add_candidate($candidate_no,$fname, $lname,$mname, $age, $gender, $height,$vitals, $address,$event_id){
            $connection = $this->sql_connection();
            if(($this -> connection_check($connection) === true)){
                $this -> _database();//select the database
                $query = "INSERT INTO candidate_info(fname,lname,mname,age,gender,height,vitals, address,event_id)".
                            "VALUES('$fname','$lname','$mname',$age,'$gender' , '$height','$vitals', '$address',$event_id)";
                $result = mysql_query($query, $connection) or die(mysql_error() . "Line 402");
                //ok

                //echo $candidate_no . " candidate no <br/>";
                if($result){
                    $query = "SELECT id FROM candidate_info WHERE fname = '$fname' AND mname = '$mname' AND lname='$lname' AND gender = '$gender' AND event_id = $event_id";
                    //echo $query;
                    $result = mysql_query($query);
                    $fetch_new_data = mysql_fetch_array($result);
                    $candidate_id = $fetch_new_data['id'];
                    $query_2 = "INSERT INTO candidate(candidate_id,candidate_no,event_id)".
                                "VALUES($candidate_id, $candidate_no, $event_id)";
                    //echo $query_2;
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
                    echo "Unsuccessful operation outer else";
                }//end if
            }
        }
        
        //function for adding official
        public function add_official($alias, $fname, $lname, $gender, $password, $designation,$admin_creator_id, $event_id){
            $username_a = $fname.$lname.$event_id.$admin_creator_id;

            if(!($this->check_official($username_a,$event_id))){

            	$_username = stripslashes($username);
            	$_alias = stripslashes($alias);
            	$_fname = stripslashes($fname);
            	$_lname = stripslashes($lname);
            	$_gender = stripslashes($gender);
            	$_password = stripslashes($password);
            	$_designation = stripslashes($designation);
            	$_admin_id = stripslashes($admin_creator_id);
            	$_event_id = stripslashes($event_id);

				$username_ = mysql_real_escape_string($_username);
				$alias_ = mysql_real_escape_string($_alias);
				$fname_ = mysql_real_escape_string($_fname);
				$lname_ = mysql_real_escape_string($_lname);
				$gender_ = mysql_real_escape_string($_gender);
				$password_ = mysql_real_escape_string($_password);
				$designation_ = mysql_real_escape_string($_designation);
				$admin_id_ = mysql_real_escape_string($_admin_id);
				$event_id_ = mysql_real_escape_string($_event_id);

				$username_ = $fname_.$lname_.$event_id_.$admin_id_;
                //echo "Not yet in db";
                //username is $fname$lname$event_id$admin-creator_id
                
                $result = $this->query("INSERT INTO official(username, password, alias, fname, lname, gender, designation,admin_creator_id, event_id)
                                VALUES('$username_', '$password_', '$alias_', '$fname_', '$lname_', '$gender_', '$designation_', $admin_id_, $event_id_)");
                return $result;
            }else
                echo "<p style='color:red'>Already in the exists.</p>";
        }
        
        //checks if the official is already existing in the table
        public function check_official($username, $event_id){
            $result = $this -> query("SELECT COUNT(id) AS id FROM official WHERE username = '$username' AND event_id = $event_id");
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
        
        public function get_officials($event_id){
            $result = $this -> query("SELECT * FROM official WHERE event_id = $event_id");
            return $result;
        }

        public function get_officials_count($event_id){
    		$result = $this -> query("SELECT COUNT(id) as id_cnt FROM official WHERE event_id = $event_id");
            $count = mysql_fetch_array($result);
            return $count['id_cnt'];
        }

        //this will return the event id designated for the official
        public function get_event_id_for_official($username){
            $result = $this -> query("SELECT * FROM official WHERE username = '$username'");
            $row = mysql_fetch_array($result);
            return $row['event_id'];
        }
        // get event with roundwith round id
        public function get_event_w_round_id($event_id,$round_id){
            $result = $this -> query("SELECT * FROM event WHERE id = $event_id AND round_id = $round_id");
            return $result;
        }
    	
    	public function get_event($event_id){
            $result = $this -> query("SELECT * FROM event WHERE id = $event_id");
            return $result;
        }

    	public function get_average_score($category_id, $candidate_id, $event_id, $round_id){
    		$result_category_score = $this->query("SELECT * FROM category_score WHERE category_id = $category_id AND candidate_id = $candidate_id AND event_id = $event_id AND round_id = $round_id");
    		$judge_count_who_scored = 0;
    		$total_score = 0;
    		$average_score = 0;
    		while($row_category_score = mysql_fetch_array($result_category_score)){
    			$judge_count_who_scored += 1;
    			$total_score += $row_category_score['score'];
    		}

    		//fetch the number of judges
    		$officials_count = $this->get_officials_count($event_id);

    		//average = total_score / judge_count
    		$average_score = $total_score / $officials_count;

    		return $average_score;
    	}
    	
		public function get_scoring_details($category_id, $candidate_id, $event_id, $round_id){
    		$result_category_score = $this->query("SELECT * FROM category_score WHERE category_id = $category_id AND candidate_id = $candidate_id AND event_id = $event_id AND round_id = $round_id");
    		$judge_count_who_scored = 0;
    		$total_score = 0;
    		$average_score = 0;
    		while($row_category_score = mysql_fetch_array($result_category_score)){
    			$judge_count_who_scored += 1;
    			$total_score += $row_category_score['score'];
    		}

    		//fetch the number of judges
    		$officials_count = $this->get_officials_count($event_id);

    		//average = total_score / judge_count
    		$average_score = $total_score / $officials_count;
			
			$result_category_max = $this->query("SELECT max_score FROM category WHERE id = $category_id");
			$row_category_max = mysql_fetch_array($result_category_max);
			$max_score =$row_category_max['max_score'];
			$scoring_details = array('judge_scored_cnt'=>$judge_count_who_scored,'judges_cnt'=>$officials_count,'average_score'=>$average_score,'max_score'=>$max_score);
			
    		return $scoring_details;
    	}

		
		public function get_total_score($category_id, $candidate_id, $event_id, $round_id){
			$result_category_score = $this->query("SELECT * FROM category_score WHERE category_id = $category_id AND candidate_id = $candidate_id AND event_id = $event_id AND round_id = $round_id");
    		//$judge_count_who_scored = 0;
    		$total_score = 0;
    		//$average_score = 0;
    		while($row_category_score = mysql_fetch_array($result_category_score)){
    			//$judge_count_who_scored += 1;
    			$total_score += $row_category_score['score'];
    		}

    		//fetch the number of judges
    		$officials_count = $this->get_officials_count($event_id);

    		//average = total_score / judge_count
    		$average_score = $total_score / $officials_count;

    		return $average_score;
		}
		
		public function add_to_round($candidate_id,$round_id,$event_id){
			//this is not a permanent solution for adding candidate to next next round, this assumes that a contest only has 2 rounds
			//$result_get_next_round = $this->query("SELECT *")

			$result_add_to_round = $this->query("INSERT INTO round_qualifiers(round_id,candidate_id, event_id) VALUES($round_id,$candidate_id,$event_id)");
			return $result_add_to_round;
		}
		
		public function is_round_qualifier($candidate_id,$round_id){
			$query="SELECT COUNT(id) AS id FROM round_qualifiers WHERE candidate_id = $candidate_id AND round_id = $round_id";
			//echo $query . "<br/>";
			$result = $this->query($query);
			$row=mysql_fetch_array($result);
			if($row['id']>0){
				return true;
			}else{
				return false;
			}
		}
		
		public function add_to_overallscore($candidate_id, $event_id, $round_id,$score,$gender){
			//check first if the candidates' score was already recorded
			//if not then insert
			$query = "SELECT candidate_id FROM overall_score WHERE candidate_id = $candidate_id AND round_id = $round_id AND gender='$gender'";
			//echo $query . "<br/>";
			
			$result_is_already_recorded = $this->query($query);
			
			$row_is_already_recorded = mysql_fetch_array($result_is_already_recorded);
			if($row_is_already_recorded){
				//echo "ALREADY RECORDED, UPDATE NEEDED<br/>";
				//if it is already recorded, update the score
				$result_update_score = $this->query("UPDATE overall_score SET score = $score WHERE event_id = $event_id AND round_id = $round_id AND candidate_id = $candidate_id AND gender='$gender'");
				if($result_update_score){
					//echo "UPDATE SUCCESSFUL";
				}else{
					echo "UPDATE FAILED";
				}
			}else{//else insert it to the table
				echo "NOT YET RECORDED<BR/>";
				$result_insert_score = $this->query("INSERT INTO overall_score(candidate_id,event_id, round_id,score,gender) VALUES($candidate_id,$event_id, $round_id,$score,'$gender')");
				if($result_insert_score){
					//echo "INSERT SUCESSFUL";
				}else{
					echo "INSERT FAILED";
				}
				
			}
		}

		public function get_top_candidates($round_id, $event_id, $limit,$gender){
			$result_get_top_candidates = $this->query("SELECT * FROM overall_score WHERE round_id = $round_id AND event_id = $event_id AND gender='$gender'	 ORDER BY `overall_score`.`score` DESC LIMIT $limit");
			return $result_get_top_candidates;
		}
		
		public function get_round_title($round_id){
			$result = $this->query("SELECT round_name FROM round WHERE id = $round_id");
			$row_round_id = mysql_fetch_array($result);
			return $row_round_id['round_name'];
		}
		
		//function for displaying all pageant/event
		//display all event/pageant on adminForm.php
		public function get_all_events(){
			$result = $this -> query("SELECT * FROM event");
			return $result;
		}
	}//end class sql_control
    


?>
