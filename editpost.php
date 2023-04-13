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

	if(isset($_GET['id'])){

		mysqli_set_charset($con,"utf8");
		$postid    		= mysqli_real_escape_string($con, $_GET['id']);
		$userid    		= mysqli_real_escape_string($con, $_COOKIE['userid']);
		$sql        	= "SELECT * FROM `group_posts` WHERE `id`=$postid AND `userID`=$userid";
		$result			= mysqli_query($con, $sql);
		if(!$result){
			echo mysqli_error($con);
		}
		else{
			while($rows=mysqli_fetch_array($result)){
				?>
				<!DOCTYPE html>
				<html>
				<head>
					<style>
					.bodyMain {
					  background-color: rgb(36, 37, 38);
					  font-family: Trebuchet MS;
					  color: white;
					  margin-right: 40px;
					  margin-left: 40px;
					  margin-top: 37px;
					}
					</style>
					<link rel="stylesheet" type="text/css" href="css/client.css"/>
					<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
					<link rel="stylesheet" href="css/fontawesome-free-6.2.0-web/css/all.min.css" />
					<script src="js/tinymce_6.2.0/tinymce/js/tinymce/tinymce.min.js" ></script>
					<script src="js/createpost.js"></script>
					<script>
					  tinymce.init({
						selector: '#editor',
						width: '100%'
					  });
					</script>
				</head>
				<body>
				<div class="bodyMain">
					<span style="font-size: 25px">Edit Post</span>&nbsp;
					<div style="float: right;">
						<span id="notific"></span>
						&nbsp; &nbsp;
						<button class="button-10" onclick="UpdatePost(<?php echo $rows['id'];?>)"><i class="fa-solid fa-floppy-disk"></i> Save</button>
						<button class="button-10" onclick="Cancel()"><i class="fa-solid fa-times"></i> Cancel</button>
					</div>
					</br>
					</br>					
					<textarea width="90%" id="editor"><?php echo base64_decode($rows['content']); ?></textarea>
				</div>
				</body>
				</html>
		<?php
			}
		}
	}
		
}else{ echo "<script>window.open('login.php','_self')</script>"; } ?>

