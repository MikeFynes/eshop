<?php 
	session_start();
	if(isset($_SESSION["manager"])){
		header("location: index.php");
		exit();
	}

	// BELOW EXECUTED WHEN USER PRESSES LOG IN BUTTON
	if(isset($_POST["username"]) && isset($_POST["password"])){
		// IGNORES EVERYTHING BUT NUMBERS AND LETTERS
		$manager = preg_replace('#[^A-Za-z0-9]#i', "", $_POST["username"]);
		$password = preg_replace('#[^A-Za-z0-9]#i', "", $_POST["password"]);
		$password = hash('sha256', $password . $manager);

		include "../scripts/connect_to_mysql.php";
		$sql = mysql_query("SELECT id FROM admin WHERE username='$manager' AND password='$password' LIMIT 1");

		// THIS CHECKS THE PERSON EXISTS IN THE DATABASE
		$existCount=mysql_num_rows($sql);
 		if ($existCount == 1) { // CHECKS IF IT IS A LEGIT USERNAME & PASSWORD
	 		while($row = mysql_fetch_array($sql)){ 
				$id = $row["id"];
		 	}
		 $_SESSION["id"] = $id;
		 $_SESSION["manager"] = $manager;
		 $_SESSION["password"] = $password;
		 $date_today = date("Y-m-d"); 
		 $sql = mysql_query("UPDATE admin SET last_log_date='$date_today'  WHERE id='$id'");
		 header("location: index.php");
    exit();
   }
   
   else {
			echo 'That information is incorrect, try again <a href="index.php">Click Here</a>';
			exit();
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background:white;">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" >
	<title>Camera WebShop - Admin Login</title>
	<link rel=stylesheet href="admin_style.css" type="text/css">
</head>
<body>
<div id="login_main_container">

<div id="login_content">
	<h2>WebShop login</h2>
	<form id="adminLogin" name="adminLogin" method="post" action="admin_login.php">
		<table id="login_table">
			<tr>
				<td class="first_cell">Username:</td>
				<td class="second_cell"><input name="username" type="text" id="username"/></td>
			</tr>
			<tr>
				<td class="first_cell">Password:</td>
				<td class="second_cell"><input name="password" type="password" id="password" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="button" class="button" id="login_button" value="Log In" /></td>
			</tr>
		</table>
	</form>
</div>

</div>
</body>
</html>
