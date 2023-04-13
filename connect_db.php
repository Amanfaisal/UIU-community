<?php

$con1 = mysqli_connect('localhost', 'root', '');
$con = mysqli_connect("localhost", "root", "", "fbgroup");
mysqli_set_charset($con, 'utf8');

mysqli_connect('localhost', 'root', '') or die(mysqli_error());
mysqli_select_db($con, 'fbgroup') or die(mysqli_error());

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fbgroup";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
?>

