<?php
	session_start();
	
	//create database connection
	include("connect_db.php");
	
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
		
		
			//search for institute and institute data and display
			if($_GET['data'] == 'like'){
				
				mysqli_set_charset($con,"utf8");
				$postID    	= mysqli_real_escape_string($con, $_GET['post_id']);
				$userID 	= $_COOKIE['userid'];
				$id 		= '';
			
			   // it return number of rows in the table.
				$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE `postID`='$postID' AND `userID`='$userID'"));
				if($row >= 1){
					$sql        = "DELETE FROM `likes` WHERE `postID`='$postID' AND `userID`='$userID'";
					$result		= mysqli_query($con, $sql);
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						$likeCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE postID='$postID'"));
						$data = array('liked' => 'false', 'LikeCount' => $likeCount);
						echo json_encode($data);
					}
				} else if($row == 0){
					$sql        = "INSERT INTO `likes` VALUES(DEFAULT, $postID, $userID)";
					$result		= mysqli_query($con, $sql);
					if(!$result){
						echo mysqli_error($con);
					}
					else{
						$likeCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE postID='$postID'"));
						$data = array('liked' => 'true', 'LikeCount' => $likeCount);
						echo json_encode($data);
					}
				}
			}



			//search for institute and institute data and display
			if($_GET['data'] == 'deletePost'){
				
				mysqli_set_charset($con,"utf8");
				$postID    	= mysqli_real_escape_string($con, $_GET['post_id']);
				$userID 	= $_COOKIE['userid'];
			
			   // it return number of rows in the table.
				$row = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `group_posts` WHERE `id`='$postID' AND `userID`='$userID'"));
				if($row >= 1){
					$sql        = "DELETE FROM `group_posts` WHERE `id`='$postID' AND `userID`='$userID'";
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
			
			
			
			//search for institute and institute data and display
			if($_GET['data'] == 'DoComment'){
				
				if(isset($_REQUEST['submit'])){

					if($_REQUEST['post_data']!= null){

						$jsonobj2 = $_REQUEST['post_data'];
						$obj1 = json_decode($jsonobj2);
						
						$postID 	= (int)mysqli_real_escape_string($con, $obj1->postID);
						$userID 	= $_COOKIE['userid'];
						$newComment = mysqli_real_escape_string($con, $obj1->commentText);
						
						
						mysqli_set_charset($con,"utf8");
						
							$Commentsql       	= "INSERT INTO `comments` VALUES(DEFAULT, $userID, $postID, '$newComment')";
							$result				= mysqli_query($con, $Commentsql);
							if(!$result){
								$data = array('commented' => 'false');
								echo json_encode($data);
							}
							else{
								
								//Get Commenter Info
								$CommentOwnerID = (int)$userID;
								if($row2 = $conn->query("SELECT * FROM users WHERE id=".$CommentOwnerID)->fetch_assoc()) {
									
									$CommentOwnerName = $row2['name'];
									$CommentOwnerInstitute = $row2['institute'];
									
									//Get Institute Name of Commenter
									if($row12 = $conn->query("SELECT * FROM institutes WHERE id='$CommentOwnerInstitute'")->fetch_assoc()) {
										$instituteName = $row12['name'];
									}
									
								}	
								
								$PostCommentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `comments` WHERE `postID`='".$postID."'"));
								
								$data = array(
											'commented' => 'true', 
											'commenterID' => $CommentOwnerID,
											'commenterName' => $CommentOwnerName,
											'instituteName' => $instituteName,
											'commentText' => $obj1->commentText,
											'CommentCount' => $PostCommentCount
											);
								echo json_encode($data);
							}
						
						//Ends Here
					}
				}	
			}





			//search for institute and institute data and display
			if($_GET['data'] == 'DoPostText'){
				
				if(isset($_REQUEST['submit'])){

					if($_REQUEST['post_data']!= null){

						$jsonobj2 = $_REQUEST['post_data'];
						$obj1 = json_decode($jsonobj2);
						
						$groupID 	= $_REQUEST['groupID'];
						$userID 	= $_COOKIE['userid'];
						$newPost	= base64_encode($obj1->postText);
						
						
						mysqli_set_charset($con,"utf8");
						
							$Commentsql       	= "INSERT INTO `group_posts` VALUES(DEFAULT, $userID, $groupID, '$newPost', DEFAULT, 'yes')";
							$result				= mysqli_query($con, $Commentsql);
							if(!$result){
								$data = array('posted' => 'false');
								echo json_encode($data);
							}
							else{
								$data = array('posted' => 'true');
								echo json_encode($data);
								}	
							}
						
						//Ends Here
					}
				}	
			


			//search for institute and institute data and display
			if($_GET['data'] == 'EditPostText'){
				
				if(isset($_REQUEST['submit'])){

					if($_REQUEST['post_data']!= null){

						mysqli_set_charset($con,"utf8");
						$jsonobj2 = $_REQUEST['post_data'];
						$obj1 = json_decode($jsonobj2);
						
						$userID 	= $_COOKIE['userid'];
						$PostID		= $obj1->postID;
						$PostText	= base64_encode($obj1->postText);
					
						$Commentsql       	= "UPDATE `group_posts` SET content='$PostText' WHERE id=$PostID AND userID=$userID";
						$result				= mysqli_query($con, $Commentsql);
						if(!$result){
							$data = array('posted' => 'false');
							echo json_encode($data);
						}else{
							$data = array('posted' => 'true', 'content' => $obj1->postText);
							echo json_encode($data);
						}	
					}
						
						//Ends Here
					}
				}	
			
			
			
				
	}
		
}else{ echo "<script>window.open('login.php','_self')</script>"; } ?>