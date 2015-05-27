<?php
require_once "check_login.php";	
include "../scripts/connect_to_mysql.php";

if (isset($_POST['edit'])) {
	if (isset($_GET['imageid'])) {
	// BELOW ADDS NEW EDITED DATA TO DATABASE
		if (isset($_POST['product_name'])) {
		$imageid = htmlspecialchars(mysql_real_escape_string($_POST['thisID']));
		$product_name = htmlspecialchars(mysql_real_escape_string($_POST['product_name']));
		$price = htmlspecialchars(mysql_real_escape_string($_POST['price']));
		$category = htmlspecialchars(mysql_real_escape_string($_POST['category']));
		$details = htmlspecialchars(mysql_real_escape_string($_POST['details']));

		// CHECK TO ENSURE ID MATCHES AN ID NUMBER IN DATABASE
		$sql = mysql_query("UPDATE products SET product_name='$product_name', price='$price', details='$details', category='$category' WHERE id='$imageid'");
		// BELOW ONLY ADDS IMAGE IF FIELD IS NOT BLANK IF FIELD IS BLANK NO IMAGE IS ADDED - OLD IMAGE IS STILL USED
		if ($_FILES['image']['tmp_name'] != "") {
			$newname = $imageid.".jpg";
			move_uploaded_file( $_FILES['image']['tmp_name'], "../inv_images/".$newname);
		}
		$_SESSION['updated'] = 1;
		header("location: index.php?slct=2"); 
		exit();
		}
	}
}
?>