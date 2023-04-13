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

?>
<?php
if(isset($_GET['id'])){
	mysqli_set_charset($con,"utf8");
	$id    		= mysqli_real_escape_string($con, $_GET['id']);
	$sql        = "SELECT * FROM `groups` WHERE `id`=$id";
	$result		= mysqli_query($con, $sql);
	if(!$result){
		echo mysqli_error($con);
	}
	else{
		while($rows=mysqli_fetch_array($result)){
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $rows['name'];?> - About</title>
	<link rel="stylesheet" type="text/css" href="../css/client.css"/>
	<link rel="stylesheet" type="text/css" href="../css/aurna-lightbox.css"/>
	<link rel="stylesheet" href="../css/fontawesome-free-6.2.0-web/css/all.min.css" />
</head>
<body>

	<script src="../js/aurna-lightbox.js"></script>
	<script src="../js/discussion.js"></script>
	<script src="../js/managecontent.js"></script>
	<script src="js/moderator.js"></script>

<ul>
	<li style='background: linear-gradient(to left,#2e76ff, #1abfff);'>
		<a href="javascript:void(0);">
		<?php
			$userid = $_COOKIE['userid'];
			if ($conn->query("SELECT username FROM users WHERE id='$userid'")->num_rows > 0) {
				// output data of each row
				if($row = $conn->query("SELECT username FROM users WHERE id='$userid'")->fetch_assoc()) {
					echo "<span>Hello! <strong>".$row['username']."</strong></span><br>";
				}
			} else {
				echo "<b>Something Went Wrong!</b>";
			}
		?>
		</a>
	</li>
	<li><a href="index.php">Your Groups</a></li>
	<li><a href="discover.php">Discover</a></li>
	<li class="dropdown">
		<a href="javascript:void(0)" class="dropbtn">Manager</a>
			<div class="dropdown-content">
			  <a href="manage/groups.php">My Groups</a>
			  <a href="manage/account.php">My Account</a>
			  <a href="manage/settings.php">Settings</a>
			</div>
	</li>
	<li style="float:right"><a class="active" href="logout.php">Logout</a></li>
</ul>


<?php 
//Page Header
//Group Cover Photo, Group Name, Group Type, Group Members Count
include("../model/PageHeader.php");
?>


<ul>
	<li><a href="../group.php?id=<?php echo $_GET['id'];?>">About</a></li>
	<li><a href="../discussion.php?id=<?php echo $_GET['id'];?>">Discussion</a></li>
	<li><a href="javascript:void(0);">Topics</a></li>
	<li><a href="../members.php?id=<?php echo $_GET['id'];?>">Members</a></li>
	<li><a href="javascript:void(0);">Events</a></li>
	<li><a href="javascript:void(0);">Media</a></li>
	<li><a href="javascript:void(0);">Files</a></li>
	<li><a href="javascript:void(0);">Chat Rooms</a></li>
	<?php
	//For Moderator
	$modresult1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `group_moderators` WHERE `userID`=".$_COOKIE['userid']." AND `groupID`=$id"));
	if(!$modresult1){
		echo mysqli_error($con);
	}
	else{
		if($modresult1 == 1){ 
	?>		
		<li class="activeMenu dropdown modsMenu">
		<a href="javascript:void(0)" class="dropbtn">Manage Group</a>
			<div class="dropdown-content">
			  <a href="#">Manage Posts</a>
			  <a href="comments.php?id=<?php echo $_GET['id'];?>">Manage Comments</a>
			  <a href="members.php?id=<?php echo $_GET['id'];?>">Add/Remove Members</a>
			</div>
	</li>
	<?php
		}
	}		
	?>
</ul>

<div class='body' style="width: 90%;">
	<h1>Manage Group - All Posts</h1>
	<table>
	<tr>
		<td>ID</td>
		<td>Content</td>
		<td>User</td>
		<td>Stat</td>
		<td>Action</td>
	</tr>
	<?php
	if(isset($_GET['id'])){

		mysqli_set_charset($con,"utf8");
		$id    		= mysqli_real_escape_string($con, $_GET['id']);
		$sql        = "SELECT * FROM `group_posts` WHERE `groupID`=$id";
		$result		= mysqli_query($con, $sql);
		if(!$result){
			echo mysqli_error($con);
		}
		else{
			while($rows=mysqli_fetch_array($result)){
	?>
		<tr id="postRow<?php echo $rows['id'];?>">
			<td><?php echo $rows['id'];?></td>
			<td><?php echo(strip_tags(base64_decode($rows['content']))); ?></td>
		<?php
				$ambid = $rows['userID'];
				if($row=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `users` Where id=$ambid"), MYSQLI_ASSOC)){
		?>		
			<td>
				<div style="display: inline-block; margin-right: 30px;">
				<a style="text-decoration: none;" href="../profile.php?id=<?php echo $row['id'];?>">
				<span style="font-size:15px; font-weight: bold; margin-bottom: 4px;"><?php echo $row['name']; ?></span></br>
				<?php
				$OwnerInstitute = $row['institute'];
				if($row1 = $conn->query("SELECT name FROM institutes WHERE id='$OwnerInstitute'")->fetch_assoc()) {
				?>
				<small> <?php echo $row1['name']; ?></small>
				</a>
				</div>
			</td>
		<?php
				}
				}
		?>
			<td>
				<span id="likeCounter<?php echo $rows['id'];?>">
				<?php 
					$PostLikeCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `likes` WHERE `postID`='".$rows['id']."'"));
					if($PostLikeCount  == 0){
						echo '<b>No Likes</b>';
					} else {
						echo $PostLikeCount.' Likes';
					}
				?></span>
				&nbsp;.&nbsp;
				<span id="commentCounter<?php echo $rows['id'];?>">
				<?php 
					$PostCommentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `comments` WHERE `postID`='".$rows['id']."'"));
					if($PostCommentCount  == 0){
						echo '<b>No Comments</b>';
					} else {
						echo $PostCommentCount.' Comments';
					}
				?></span>
			</td>
			<td>
				<button onclick="DeletePostMods(<?php echo $rows['id'];?>, <?php echo $id;?>)" class="button-10">Delete Post</button>
			</td>
		</tr>
		<?php
				
			}
		}
	}
	?>
	</table>
	
</div>
	
	
	
</body>
</html>


<?php
		}
	}

 }	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>
 
 
 
 
 
 