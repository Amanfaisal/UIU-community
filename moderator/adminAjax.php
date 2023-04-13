<?php
	session_start();
	
	//create database connection
	include("../connect_db.php");
	
	//blank var
	$getsessionID = '';
	
	//call session data
	if(isset($_COOKIE['sessionid'])){
		//get session id from browser and update variable
		$getsessionID = $_COOKIE['sessionid'];
	}
	//set the validity mode for session data
	$validity = "valid";	
	//verify session id
	if(mysqli_num_rows(mysqli_query($con, "select * from sessions where session_id='$getsessionID' AND validity='$validity'"))> 0){
		
		
		

	
	//get requests for data
	if(isset($_GET['data'])){
		
			//Delete Posts By Moderator
			if($_GET['data'] == 'deletePost'){
				
				mysqli_set_charset($con,"utf8");
				$postID    	= mysqli_real_escape_string($con, $_GET['post_id']);
				$groupID    	= mysqli_real_escape_string($con, $_GET['group_id']);
				$userID 	= $_COOKIE['userid'];
			
			   
				
				//For Moderator
				$modresult1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `group_moderators` WHERE `userID`=".$_COOKIE['userid']." AND `groupID`=$groupID"));
				if(!$modresult1){
					echo mysqli_error($con);
				}
				else{
					if($modresult1 == 1){ 
					
					  
					  // it return number of rows in the table.
						$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `group_posts` WHERE `id`='$postID'"));
						if($row >= 1){
							$sql        = "DELETE FROM `group_posts` WHERE `id`='$postID'";
							$result		= mysqli_query($con, $sql);
							if(!$result){
								echo mysqli_error($con);
							}
							else{
								$data = array('deleted' => 'true');
								echo json_encode($data);
							}
						}
						
						
					}
				}		

			}
			//Ends Sections	
			
			
			
				
	}
		
}else{ echo "<script>window.open('login.php','_self')</script>"; } ?>