<?php
include "./scripts/connect_to_mysql.php";

// CHECK WHICH CATEGORY SELECTED. GO BACK TO INDEX.PHP IN CASE OF AN ERROR
if (isset($_GET['ctgr'])) {
	preg_replace('#[^0-9]#i', '',$_GET['ctgr']);
	if ($_GET['ctgr'] == 1) $category = 'Camera';
	else if ($_GET['ctgr'] == 2) $category = 'Lens';
	else if ($_GET['ctgr'] == 3) $category = 'Accessory';
	else {
		header("location: index.php"); 
		exit();
	}
}
else {
	header("location: index.php"); 
	exit();
}

// THIS ALLOWS CATEGORY ITEMS TO BE PRINTED
$product_list = "";
$sql = mysql_query("SELECT * FROM products WHERE category = '".$category."' ORDER BY product_name");
$productCount = mysql_num_rows($sql);
	
// CHECKS IF PRODUCT AMOUNT IS GREATER THAN 0, IF NOT A MESSAGE IS ECHOED
if ($productCount > 0) {

	// PRODUCT LIST HEADERS
	$product_list .= '
		<div id="topbar"><h2>'.$category.'</h2></div>
		<div id="block_container">
	';

	// PRODUCT LIST DATA
	while($row = mysql_fetch_array($sql)) {
		$id = $row["id"];
		$product_name = $row["product_name"];
		$price = $row["price"];
		$product_list .= '
			<div class="block">
				<a href="index.php?id=' . $id . '"><img src="inv_images/' . $id . '.jpg" alt="' . $product_name . '"/></a>
				<p><b>'.$product_name.'</p></b><p>'.$price.'€</p>
			</div>
		';
	}
	
	// PRODUCT LIST FOOTER
	$product_list .= '
		<br style="clear: left;" />
		</div>
	';
}
else {
	$product_list = "ERROR - Category empty";
}

// PRINT THE PRODUCT LIST
echo $product_list; 
 
 ?>
