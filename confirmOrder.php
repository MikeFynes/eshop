<?php
include "scripts/connect_to_mysql.php"; 

// DATA FROM FORM
$firstName= htmlspecialchars(mysql_real_escape_string($_POST["givenFirstName"]));
$lastName= htmlspecialchars(mysql_real_escape_string($_POST["givenLastName"]));
$email= htmlspecialchars(mysql_real_escape_string($_POST["givenEmail"]));	
$phone= htmlspecialchars(mysql_real_escape_string($_POST["givenTelephone"]));	
$address= htmlspecialchars(mysql_real_escape_string($_POST["givenAddress"]));	
$city= htmlspecialchars(mysql_real_escape_string($_POST["givenCity"]));	
$postCode= htmlspecialchars(mysql_real_escape_string($_POST["givenPostCode"]));	

// CART DATA AND DATABASE INPUT
$cartOutput = "";
$cartTotal = "";
$product_id_array = '';
session_start();
if (isset($_SESSION["cart_array"])) {
	
// IF CART IS EMPTY
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = "<h2 align='center'>Your shopping cart is empty no charge for you!</h2>";
}
else {
	// IF CART IS NOT EMPTY GRABS INFO
	// GENERATES ORDER NUMBER AND CHECKS IF IT EXISTS IN DATABASE ALREADY (IF IT DOES IT LOOPS BACK UP AND A NEW ORDER NUMBER IS GENERATED)
	$this_order = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
	$sql = mysql_query("SELECT order_number FROM transactions WHERE order_number='$this_order' LIMIT 1");
	$numRows = mysql_num_rows($sql);
	if ($numRows > 0){
		header("location: confirmOrder.php");
	} 
	else {
		$orderNumber = $this_order;
	}
	$i = 0; 
	foreach ($_SESSION["cart_array"] as $each_item) {
		$item_id = $each_item['item_id'];
		$quantity = $each_item['quantity'];
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
		// OUTPUT
		$cartOutput .= "<tr>";
		$cartOutput .= '<td>' . $item_id . '</td>';
		$cartOutput .= '<td>' . $product_name . '</td>';
		$cartOutput .= '<td>' . $details . '</td>';
		$cartOutput .= '<td>' . $quantity . '</td>';
		$cartOutput .= '<td>' . $price . ' Euro</td>';
		$cartOutput .= '</tr>';
		$i++; 
		
		$sql = mysql_query("INSERT INTO transactions(product_name, quantity, payer_email, first_name, last_name, payment_date, order_number, address, city, postcode, phone_number, cart_total) 
		VALUES('$product_name','$quantity','$email','$firstName', '$lastName', now(), '$orderNumber', '$address', '$city', '$postcode', '$phone', '$cartTotal')") or die (mysql_error());
	} // FOR LOOP CLOSING
	
	$cartTotalOutput = "<div id='cartTotal'>Total Cost: ".$cartTotal." Euro</div>";
} // IF CLOSING CART EMPTY CHECK
	
// ADDS ABOVE DATA TO DATABASE

} // SESSION IF CLOSING

//Email info
$subject = "Your Order!";
$message ='<h2>Dear '.$firstName.' '.$lastName.'</h2><p>Your order has been received and will be processed, you have ordered the following items:</p>
		<h3>Your Order Number is: '.$orderNumber.'</h3>
		<table border="1"><tr>
		<td>Item Id</td>
		<td>Product Name</td>
		<td>Details</td>
		<td>Quantity</td>
		<td>Price per item</td>
		</tr>
		'. $cartOutput .'
		</table><br />
'.$cartTotalOutput.'
<p>Your order and invoice will be shipped shortly<p>';

$to= $email;
$from = "Your Friendly Camera Webshop";
$headers = "MIME-Version: 1.0" . "\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\n";
$headers .= "From: $from" . "\n";
//Action
mail($to,$subject,$message, $headers);

unset($_SESSION["cart_array"]);
$_SESSION["order_confirmed"] = 1;
header("location: index.php");
exit();
?>