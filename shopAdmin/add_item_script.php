<?php
//check if form is sent
if (isset($_POST['create'])) {
	
	include "../scripts/connect_to_mysql.php"; 	
	
		// CHECKS TO SEE IF PRODUCT ALREADY EXISTS
	$sql = mysql_query("SELECT id FROM posters WHERE poster_name='$id' LIMIT 1");
	$productMatch = mysql_num_rows($sql); 
	if ($productMatch > 0) {
		echo 'Sorry you tried to place a duplicate "Product Name" into the system, <a href="index.php">click here</a>';
		exit();
	}

	// ADDS TO DATABASE
	
	$imageid = mysql_insert_id();

	// RENAMES IMAGE AND ADDS IMAGE TO IMAGE FOLDER
	$newname = $imageid.".jpg";
	move_uploaded_file( $_FILES['image']['tmp_name'], "../inv_images/".$newname);
    
	if ($_POST['created'] == '0') echo '<div id="item_created">';
		else echo '<div id="item_created2">';

	// echo the product name
	echo "New product created: <b>".$_POST['product_name']."</b><br /><br />";
	echo '</div>';
}
?>