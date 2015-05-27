<?php
	session_start();
	if (!isset($_SESSION["manager"])) {
		header("location: admin_login.php"); 
		exit();
	}
	
	// CHECKS MANAGER EXISTS IN DATABASE
	// IGNORES EVERYTHING BUT NUMBERS AND LETTERS
	$managerID = preg_replace('#[^0-9]#i', "", $_SESSION["id"]);
	$manager = preg_replace('#[^A-Za-z0-9]#i', "", $_SESSION["manager"]);
	$password = preg_replace('#[^A-Za-z0-9]#i', "", $_SESSION["password"]);

	include "../scripts/connect_to_mysql.php";
	$sql = mysql_query("SELECT id FROM admin WHERE username='$manager' AND password='$password' LIMIT 1");
	// THIS CHECKS THE PERSON EXISTS IN THE DATABASE

	$existCount=mysql_num_rows($sql);
	if($existCount==0){
		echo 'Your login session data is not on record in the database. <a href="logout.php">Clear session data</a>';
		exit();
	}
?>
