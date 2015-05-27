<?php 
if (isset($_GET['id'])) {
	include "scripts/connect_to_mysql.php"; 
	$id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	
	$sql = mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1");
	$productCount = mysql_num_rows($sql); 
	if ($productCount > 0) {
		while($row = mysql_fetch_array($sql)){ 
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
			$category = $row["category"];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			$productShow='
				<div id="block_container">
				  <div class="product">
				  <img src="inv_images/' . $id . '.jpg" alt="' . $product_name . '"/>
				  <p><b>' . $product_name . '</b></p>
				  <p>' . $price . ' €</p>
				  <p>' . $details . '</p>
				  <p>' . $category . '</p>
				  <br />
			';
			if (isset($_SESSION['added'])) {
				$productShow.='
					<p>
					  <form id="addToCartButton" name="addToCartButton" method="post" action="cart.php">
					    <input type="hidden" name="imageid" id="imageid" value="'.$id.'" />
					    <input type="submit" name="button" id="button" value="Add to Shopping Cart" />
					    <b>Product added to your shopping cart.</b> <a href="index.php?showcart=1" class="cartlink">Show cart.</a>
					  </form>
					</p>
					</div>
					</div>
					<br style="clear: left;" />
				';
				unset($_SESSION['added']);
			}
			else {
				$productShow.='
				<p>
				  <form id="addToCartButton" name="addToCartButton" method="post" action="cart.php">
				    <input type="hidden" name="imageid" id="imageid" value="'.$id.'" />
					<input type="submit" name="button" id="button" value="Add to Shopping Cart" />
				  </form>
				</p>
				</div>
				</div>
				<br style="clear: left;" />
				';
			}

		}
	}
	
	else {
		header("location: index.php"); 
		exit();
	}
}

else {
	echo "Data to render this page is missing.";
	exit();
}

mysql_close();

// PRINT THE PRODUCT INFO
echo '<div id="topbar"><h2>'.$product_name.'</h2></div>'.$productShow;

?>
