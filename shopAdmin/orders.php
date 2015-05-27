<?php
require_once "check_login.php";	
include "../scripts/connect_to_mysql.php";

// ALLOWS ITEM TO BE DELETED
if (isset($_GET['deleteid'])) {
	echo 'Do you really want to delete order with ID of ' . $_GET['deleteid'] . '? <a href="index.php?slct=5&yesdelete=' . $_GET['deleteid'] . '">Yes</a> | <a href="index.php?slct=3">No</a>';
	exit();
}
 if (isset($_GET['yesdelete'])) {
	$id_to_delete = preg_replace('#[^0-9]#i', '',$_GET['yesdelete']);
	$sql = mysql_query("DELETE FROM transactions WHERE id='$id_to_delete' LIMIT 1") or die (mysql_error());
	header("location: index.php?slct=5"); 
    exit();
}
  
if (isset($_GET['order_number'])) {
	// GRABS PRODUCT INFO AND ADDS TO FORM FOR EDITING
	if (isset($_GET['order_number'])) {
		$targetID = $_GET['order_number'];
	
		// BELOW FINDS ALL ITEMS IN DATABASE AND DISPLAYS THEM
		$order_list= "";
		$sql= mysql_query("SELECT * FROM transactions WHERE id=".$targetID." LIMIT 1");
		$orderCount=mysql_num_rows($sql);
		if($orderCount>0){
			while($row= mysql_fetch_array($sql)){
				$id=$row["id"];
				$product_name=$row["product_name"];
				$quantity = $row["quantity"];
				$payer_email= $row["payer_email"];
				$firstName =$row["first_name"];
				$lastName =$row["last_name"];
				$order_number =$row["order_number"];
				$address =$row["address"];
				$city =$row["city"];
				$postcode =$row["postcode"];
				$phone_number =$row["phone_number"];
				$cart_total =$row["cart_total"];
				$date_added =strftime("%b %d, %Y", strtotime($row["payment_date"]));
			}
		}
		else {
			echo ("That file does not exist on the database");	
			exit();
		}
	}

	echo '
	<h2>Show Order '.$order_number.'</h2>
	<table id="create_table">
		<tr>
			<td class="first_cell">Product name:</td>
			<td class="second_cell">'.$product_name.'</td>
		</tr>
		<tr>
			<td class="first_cell">Quantity:</td>
			<td class="second_cell">'.$quantity.'</td>
		</tr>
		<tr>
   	<td class="first_cell">Payer Email:</td>
   	<td class="second_cell">'.$quantity.'</td>
		</tr>
		<tr>
			<td class="first_cell">First Name:</td>
   	<td class="second_cell">'.$firstName.'</td>
   </tr>
		<tr>
		<td class="first_cell">Last Name:</td>
			<td colspan="2">'.$lastName.'</td>
   </tr>
   <tr>
		<td class="first_cell">Address:</td>
			<td colspan="2">'.$address.'</td> 
	</tr>
	<tr>
		<td class="first_cell">City:</td>
			<td colspan="2">'.$city.'</td> 
	</tr>
	<tr>
		<td class="first_cell">Postcode:</td>
			<td colspan="2">'.$postcode.'</td> 
	</tr>
	<tr>
		<td class="first_cell">Phone Number:</td>
			<td colspan="2">'.$phone_number.'</td> 
	</tr>
	<tr>
		<td class="first_cell">Total price:</td>
			<td colspan="2">'.$cart_total.'</td> 
	</tr>
	<tr>
		<td class="first_cell">Order Date:</td>
			<td colspan="2">'.$date_added.'</td> 
	</tr>
	<tr>
		<td></td>
		<td id="delete_button"><a href="index.php?slct=5&deleteid='.$id.'"><input type="button" class="button" value="delete" /></a></td>
	</tr>
	</table>
	</form>';
}
else {				
	// THIS ALLOWS INVENTORY LIST TO BE PRINTED
	$order_list = "";
	$sql = mysql_query("SELECT * FROM transactions ORDER BY order_number, payment_date");
	$orderCount = mysql_num_rows($sql);
	
	// CHECKS IF ORDER AMOUNT IS GREATE THAN 0, IF NOT A MESSAGE IS ECHOED
	if ($orderCount > 0) {
		// TABLE HEADERS
		$order_list .= "
		<div class=\"tablelist\">
		<table border=\"1\">
		<tr>
			<th>ID</td>
			<th>Product Name</td>
			<th>Last Name</td>
			<th>Order Number</td>
			<th>Created</td>
		</tr>";
		
		// TABLE DATA
		while($row = mysql_fetch_array($sql)){
			$id = $row["id"];
			$product_name = $row["product_name"];
			$lastName = $row["last_name"];
			$order_number = $row["order_number"];
			$date_added = strftime("%b %d, %Y", strtotime($row["payment_date"]));
			$order_list .= "
			<tr>
				<td class=\"table_id\">$id</td>
				<td class=\"table_product\"><a href=\"index.php?slct=5&order_number=$id\">$product_name</a></td>
				<td class=\"table_price\">$lastName</td>
				<td class=\"table_price\">$order_number</td>
				<td class=\"table_created_on\">$date_added</td>
			</tr>";
		}
		
		// END TABLE
		$order_list .= "</table></div>";
	}
	else {
		$order_list = "You have no products listed in your store yet";
	}

	echo "<h2>Order List</h2>";
	echo $order_list;
}
?>

<script type="text/javascript">
	// push effect after ticket created
	if (document.getElementById('updated_notification'))
	{
	  var element = document.getElementById('updated_notification');
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
	}
</script>