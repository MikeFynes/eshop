<?php
 require_once "check_login.php";	
 include "../scripts/connect_to_mysql.php";

if (isset($_GET['userid'])) {
	$targetID = preg_replace('#[^0-9]#i', '',$_GET['userid']);
	
	// BELOW ADDS NEW EDITED DATA TO DATABASE
	if (isset($_POST['edit_user'])) {
   	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);

   	// UPDATE PASSWORD
   	if ($password != ''){
	$password = hash('sha256', $password . $username);
   	$sql = mysql_query("UPDATE admin SET password='$password' WHERE id='$targetID'");
   	$_SESSION['updated'] = 1;
	}
 		header("location: index.php?slct=3");
   		exit();
	}

	// GRABS PRODUCT INFO AND ADDS TO FORM FOR EDITING	
	$sql= mysql_query("SELECT * FROM admin WHERE id=".$targetID." LIMIT 1");
	$userCount=mysql_num_rows($sql);
	if($userCount>0){
		while($row= mysql_fetch_array($sql)){	
			$username=$row["username"];
		}
	} else {
		echo ("That file does not exist on the database");	
		exit();
	}

	echo '
	<form action="./index.php?slct=3&userid='.$targetID.'" method="post">
	<h2>Edit user</h2>
	<table id="edit_user_table">
  		<tr>
			<td class="first_cell">Username:</td>
			<td class="second_cell"><input type="text" name="username" readonly="true" value="'.$username.'"></input></td>
		</tr>
    	<tr>
    		<td class="first_cell">Password:</td>
    		<td class="second_cell"><input type="password" name="password" value=""></input></td>
  		</tr>
  		<tr>
    		<input name="thisID" type="hidden" value="'.$targetID.'" />
			<td id="delete_button"><a href="index.php?slct=3&deleteid='.$targetID.'"><input type="button" class="button" value="delete" /></a></td>
			<td id="create_button"><input class="button" type="submit" name="edit_user" value="Change password"></td>
    	</tr>
  	</table>
	</form>
	';

}
else {
	// show notification if information is updated
	if (isset($_SESSION['updated']))
	{
		echo '<div id="updated_notification"><b>Password changed!</b></div>';
		unset($_SESSION['updated']);
	}

	// ALLOWS ITEM TO BE DELETED
	if (isset($_GET['deleteid'])) {
		echo 'Do you really want to delete user with ID of ' . $_GET['deleteid'] . '? <a href="index.php?slct=3&yesdelete=' . $_GET['deleteid'] . '">Yes</a> | <a href="index.php?slct=3">No</a>';
		exit();
	}

	if (isset($_GET['yesdelete'])) {
		$id_to_delete = preg_replace('#[^0-9]#i', '',$_GET['yesdelete']);
		$sql = mysql_query("DELETE FROM admin WHERE id='$id_to_delete' LIMIT 1") or die (mysql_error());
		header("location: index.php?slct=3"); 
		exit();
	}

	// THIS ALLOWS USER NAMES TO BE PRINTED
	$user_list = "";
	$sql = mysql_query("SELECT * FROM admin ORDER BY last_log_date DESC");
	$userCount = mysql_num_rows($sql);
	
	// CHECKS IF USER COUNT IS GREATER THAN 0, IF NOT A MESSAGE IS ECHOED
	if ($userCount > 0) {
		// TABLE HEADERS
		$user_list .= "
		<div class=\"tablelist\">
		<table border=\"1\">
		<tr>
			<th>ID</td>
			<th>Username</td>
			<th>Last seen</td>
			<th>Edit</td>
		</tr>";
		
		// TABLE DATA

		while($row = mysql_fetch_array($sql)){
			$id = $row["id"];
			$username = $row["username"];
			$last_seen = $row["last_log_date"];
			$user_list .= "
			<tr>
				<td class=\"table_id\">$id</td>
				<td class=\"table_username\">$username</td>
				<td class=\"table_last_seen\">$last_seen</td>
				<td class=\"table_created_on\"><a href=\"index.php?slct=3&userid=$id\">Edit</a></td>
			</tr>";
		}
		
		// END TABLE
		$user_list .= "</table></div>";

	}
	else {
		$user_list = "You have no users in your database";
	}
	
	echo "<h2>User list</h2>";
	echo $user_list;
}
?>

