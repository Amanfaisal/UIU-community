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
	<link rel="stylesheet" type="text/css" href="css/client.css"/>
	<link rel="stylesheet" type="text/css" href="css/aurna-lightbox.css"/>
	<link rel="stylesheet" href="css/fontawesome-free-6.2.0-web/css/all.min.css" />
</head>
<body>

	<script src="js/aurna-lightbox.js"></script>
	<script src="js/discussion.js"></script>
	<script src="js/managecontent.js"></script>

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
	//Member Verification, Show Content if User is Member
	$ShowContent = 0;
	if($rows['type'] == 'private'){
		//For General User
		$CurrentUserId = $_COOKIE['userid'];
		$memresult1 = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `group_members` WHERE `userID`=".$CurrentUserId." AND `groupID`=$id"));
		if(!$memresult1){
			echo mysqli_error($con);
		}
		else{
			if($memresult1 == 1){ 
				$ShowContent = 1;
			}
		}		
	}else if($rows['type'] == 'public'){
		$ShowContent = 1;
	}
	if($ShowContent == 0){
		include("model/NotMember.php");
	} else if($ShowContent == 1){
	
?>

<?php 
//Page Header
//Group Cover Photo, Group Name, Group Type, Group Members Count
include("model/PageHeader.php");
?>


<ul>
	<li><a href="group.php?id=<?php echo $_GET['id'];?>">About</a></li>
	<li><a href="discussion.php?id=<?php echo $_GET['id'];?>">Discussion</a></li>
	<li><a href="javascript:void(0);">Topics</a></li>
	<li><a class="activeMenu" href="#">Members</a></li>
	<li><a href="javascript:void(0);">Events</a></li>
	<li><a href="javascript:void(0);">Media</a></li>
	<li><a href="javascript:void(0);">Files</a></li>
	<li><a href="javascript:void(0);">Chat Rooms</a></li>
</ul>

<div class='body' style="width: 80%;">
	<h1>Members</h1>

 
	<?php
	if(isset($_GET['id'])){

		mysqli_set_charset($con,"utf8");
		$id    		= mysqli_real_escape_string($con, $_GET['id']);
		$sql        = "SELECT * FROM `group_members` WHERE `groupID`=$id";
		$result		= mysqli_query($con, $sql);
		if(!$result){
			echo mysqli_error($con);
		}
		else{
			while($rows=mysqli_fetch_array($result)){

				$ambid = $rows['userid'];
				if($row=mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `users` Where id=$ambid"), MYSQLI_ASSOC)){
		?>		
				<div style="display: inline-block; margin-right: 30px; margin-bottom: 20px">
				<a style="text-decoration: none;" href="profile.php?id=<?php echo $row['id'];?>">
				<img style="height: 42px; border-radius: 50px; position: absolute;" src="uploads/<?php echo $row['image'];?>"/>
				<span style="font-size:20px; margin-bottom: 4px; margin-left: 49px;"><?php echo $row['name']; ?></span></br>
				
				<?php
				$OwnerInstitute = $row['institute'];
				if($row1 = $conn->query("SELECT name FROM institutes WHERE id='$OwnerInstitute'")->fetch_assoc()) {
				?>
				
				<small style="margin-left: 49px;"> <?php echo $row1['name']; ?></small>
				
				</a>
				</div>
		<?php
				}
				}
			}
		}
	}
	?>

	
</div>
	
<?php 
//End User Content
} 

?>		
	
</body>
</html>


<?php
		}
	}

 }	}	else { echo "<script>window.open('login.php','_self')</script>"; } ?>
 
 
 
 
 