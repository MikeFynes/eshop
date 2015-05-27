<?php 
session_start();

include "scripts/connect_to_mysql.php"; 

// THIS RUNS IF ITEM IS ADDED TO THE SHOPPING CART
if (isset($_POST['imageid'])) {
    $imageid = preg_replace('#[^0-9]#i', '', $_POST['imageid']);
	$wasFound = false;
	$i = 0;
	// IF SHOPPING CART SESSION NOT RUNNING OR IF CART IS EMPTY THIS RUNS
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $imageid, "quantity" => 1));
	} else {
		// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
		foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $imageid) {
					  // IF ITEM IS ALREADY IN CART BUT IS ADD TO CART IS PRESSED AGAIN THIS INCREASES QUANTITY
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $imageid, "quantity" => $each_item['quantity'] + 1)));
					  $wasFound = true;
				  } 
		      } 
	       } 
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $imageid, "quantity" => 1));
		   }
	}
	$_SESSION['added'] = 1;
	header("location: index.php?id=$imageid"); 
    exit();
}



// ALLOWS CART TO BE EMPTIED


if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}



// ALLOWS QUANTITY TO BE ADJUSTED
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		$i++;
		while (list($key, $value) = each($each_item)) {
			if ($key == "item_id" && $value == $item_to_adjust) {
				// That item is in cart already so let's adjust its quantity using array_splice()
				array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
			} // close if condition
		} // close while loop
	} // close foreach loop
}

// ALLOWS ITEM TO BE REMOVED
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
	$key_to_remove =  preg_replace('#[^0-9]#i', '', $_POST['index_to_remove']);
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}

// CART ARRAYS
$cartOutput = "";
$cartTotal = "";
$product_id_array = '';
session_start();
if (isset($_SESSION["cart_array"])) {
	// IF CART IS EMPTY
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
		$cartOutput = '<h2>Your shopping cart is empty</h2>';
	} 
	else {
		// IF CART IS NOT EMPTY GRABS INFO
		$i = 0;
		// TABLE HEADERS
		$cartOutput .= '<table>';
		$cartOutput .= '<tr>';
		$cartOutput .= '<th>Product</th>';
		$cartOutput .= '<th>Product Description</th>';
		$cartOutput .= '<th>Unit Price</th>';
		$cartOutput .= '<th>Quantity</th>';
		$cartOutput .= '<th>Total</th>';
		$cartOutput .= '<th>Remove</th>';
		$cartOutput .= '</tr>';
		
		foreach ($_SESSION["cart_array"] as $each_item) { 
			$item_id = $each_item['item_id'];
			$sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
			while ($row = mysql_fetch_array($sql)) {
				$product_name = $row["product_name"];
				$price = $row["price"];
				$details = $row["details"];
			}
		// CALCULATES TOTAL PRICE
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
		$pricetotal = money_format("%10.2n", $pricetotal);
		
		// DISPLAYS OUTPUT OF CART
		$product_id_array .= "$item_id-".$each_item['quantity'].","; 
		
		// CREATES CART DYNAMICALLY
		$cartOutput .= '<tr>';
		$cartOutput .= '<td class="cart_product"><a href="index.php?id=' . $item_id . '">' . $product_name . '</a><img src="inv_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="30" height="30" border="1" /></td>';
		$cartOutput .= '<td class="cart_details">' . $details . '</td>';
		$cartOutput .= '<td class="cart_price">' . $price . ' €</td>';
		$cartOutput .= '<td class="cart_quantity">
			<form action="index.php?showcart=1" method="post">
				<input name="quantity" type="text" value="' . $each_item['quantity'] . '" size="1" maxlength="2" />
				<input name="adjustBtn' . $item_id . '" type="submit" value="change" />
				<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
			</form></td>';
		$cartOutput .= '<td class="cart_price">' . $pricetotal . ' €</td>';
		$cartOutput .= '<td class="cart_delete"><form action="index.php?showcart=1" method="post"><input name="deleteBtn' . $item_id . '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .= '</tr>';
		$i++; 
    } 
	// END TABLE
	$cartOutput .= '</table>';
	
	$cartTotal = '<div id="cartTotal">Total Cost: '.$cartTotal.' €</div>
	<div id="emptyCart"><a href="index.php?showcart=1&cmd=emptycart">Empty Your Shopping Cart</a></div>';
	
	// PAYMENT FORM
	$form='
	<div id="payment">
		<h2> Contact Details and Order confirmation</h2>
		<p>Payment details and a full confirmation of your order will be sent to your e-mail address</p>
		<form id="confirmOrder" name="confirmOrder" action="confirmOrder.php" method="post">
			First name:<br>
			<input type="text" name="givenFirstName"><br>
			Last name:<br>
			<input type="text" name="givenLastName"><br>
			E-mail address:<br>
			<input type="text" name="givenEmail"><br>
			Telephone Number:<br>
			<input type="text" name="givenTelephone"><br>
			Address:<br>
			<input type="text" name="givenAddress"><br>
			City:<br>
			<input type="text" name="givenCity"><br>
			Postcode:<br>
			<input type="text" name="givenPostCode"><br>
			<br><br>
			<input type="submit" value="Place Order"><input type="reset" value="Clear">
		</form>
	</div>';
	}
}
else {
	// PRINT A MESSAGE IF THE CART IS EMPTY
	$cartOutput = '<h2>Your shopping cart is empty</h2>';
}
	
?>
	
<div id="topbar"><h2>Shopping cart</h2></div>
<div id="cart">
	<br />
	<?php echo $cartOutput; ?>
	<?php echo $cartTotal; ?>
	<?php echo $form; ?>
</div>  
<br />
<br />
<br />
<br />
