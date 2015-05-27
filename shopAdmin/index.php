<?php
	require_once "check_login.php";	
	include "add_item_script.php";
	include "edit_item_script.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
      	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title> WebShop - Admin panel </title>
        <link rel=stylesheet href="admin_style.css" type="text/css">
</head>
<body>

<div id="main_container">

      	<div id="navigation">
                <h2>Admin panel</h2>
		<h3>Inventory management</h3>                
                <ul>
                    <li><a href="index.php?slct=1">New</a></li>
                    <li><a href="index.php?slct=2">List</a></li>
					<li><a href="index.php?slct=5">View Orders</a></li>
                </ul>
		<h3>User management</h3>
               	<ul>
               				<li><a href="index.php?slct=4">Add user</a></li>
                    	<li><a href="index.php?slct=3">Users</a></li>
						
                </ul>
                <h3>Navigate</h3>  
		<ul>
		    <li><a href="../index.php" target="_blank">WebShop</a></li>
                    <li><a href="logout.php">Log out</a></li>
                </ul>
		<small>Logged in as <?php echo  $manager ?></small>
        </div>

        <div id="container">
                <div id="content">
                        <?php
                                // display the right page according to selection
                                if (isset($_GET['slct']))
                                {
                                        if ($_GET['slct'] == 1) include 'new_item.php';
                                        else if ($_GET['slct'] == 2) include 'list_items.php';
										else if ($_GET['slct'] == 3) include 'list_users.php';
                                        else if ($_GET['slct'] == 4) include 'new_user.php';
										else if ($_GET['slct'] == 5) include 'orders.php';
										
                                        else echo 'ERROR - Invalid selection';
                                }
                                else
                                {
                                        echo 'Make a selection from the left.';
                                }
                        ?>
                </div>
        </div>
        
</div>
</body>
</html>
