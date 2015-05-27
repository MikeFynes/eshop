<?php
require_once "check_login.php";	

// BELOW ADDS NEW USER DATA TO THE DATABASE
if (isset($_POST['add_user'])) {
	$username = htmlspecialchars(mysql_real_escape_string($_POST['username']));
	$password = htmlspecialchars(mysql_real_escape_string($_POST['password']));
	$password2 = htmlspecialchars(mysql_real_escape_string($_POST['confirm_password']));

	// CHECK FOR EMPTY FIELDS
 	if ($password == '' || $username == ''){
		echo 'Empty fields are not allowed. <a href="index.php?slct=4">Go Back</a>';
		exit(); 	
 	}
 	
 	// CHECK THAT PASSWORD FIELDS MATCH
 	if ($password != $password2){
		echo 'Passwords do not match. <a href="index.php?slct=4">Go Back</a>';
		exit(); 	
 	}
 	
 	$sql = mysql_query("SELECT id FROM admin WHERE username='$username' LIMIT 1");
	$productMatch = mysql_num_rows($sql); 
	if ($productMatch > 0) {
		echo 'The username is already taken. <a href="index.php?slct=4">Go Back</a>';
		exit();
	}

	// ADDS TO DATABASE
	$password = hash('sha256', $password . $username);
	$sql = mysql_query("INSERT INTO admin (username, password) 
	VALUES('$username','$password')") or die (mysql_error());
	$_SESSION['updated'] = 1;
}

// show notification if information is updated
if (isset($_SESSION['updated']))
{
	echo '<div id="updated_notification"><b>User created!</b></div>';
	unset($_SESSION['updated']);
}

?>

<form action="./index.php?slct=4" method="post">
<h2>Add new user</h2>
<table id="edit_user_table">
	<tr>
		<td class="first_cell">Username:</td>
		<td class="second_cell"><input type="text" name="username" value=""></input></t$
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td class="first_cell">Password:</td>
    	<td class="second_cell"><input type="password" name="password" value=""></input></td>
	</tr>
	<tr>
		<td class="first_cell">Confirm password:</td>
    	<td class="second_cell"><input type="password" name="confirm_password" value=""></input></td>
	</tr>
	<tr>
		<td></td>
		<td id="create_button"><input class="button" type="submit" name="add_user" value="Create"></td>
	</tr>
</table>
</form>
  
<script type="text/javascript">
       	// push effect after ticket created
        if (document.getElementById('item_created'))
        {
          var element = document.getElementById('item_created');
          var duration = 200;  /* 1000 millisecond = 1 sec */
          var height = 0;

          /* set the height of the element (0-30px) */
          function setHeight(height)
          {
            element.style.height = height+"px";
      }

          function pushIn()
          {
        for (var i = 0; i <= 30; i++)
                {
          setTimeout("setHeight(" + i + ")", i * duration / 30);
       	}
      }

          /* start the effect */
          pushIn();
          document.getElementById('created').value="1";
        }
        if (document.getElementById('item_created2')) document.getElementById('created').value="1";
</script>