<?php
require_once "check_login.php";	
include "../scripts/connect_to_mysql.php";

if (isset($_GET['imageid'])) {		 
	// GRABS PRODUCT INFO AND ADDS TO FORM FOR EDITING
	if (isset($_GET['imageid'])) {
		$targetID = $_GET['imageid'];	
		// BELOW FINDS ALL ITEMS IN DATABASE AND DISPLAYS THEM
		$product_list= "";
		$sql= mysql_query("SELECT * FROM products WHERE id=".$targetID." LIMIT 1");
		$productCount=mysql_num_rows($sql);
		if($productCount>0){
			while($row= mysql_fetch_array($sql)){
				$product_name=$row["product_name"];
				$price = $row["price"];
				$category= $row["category"];
				$details =$row["details"];
				$date_added =strftime("%b %d, %Y", strtotime($row["date_added"]));
			}
		}
		else {
			echo ("That file does not exist on the database");	
			exit();
		}
	}
	echo '
	<form action="./index.php?slct=2&imageid='.$targetID.'" method="post" enctype="multipart/form-data">
	<h2>Edit Inventory Item</h2>
	<table id="create_table">
		<tr>
			<td class="first_cell">Product name:</td>
			<td class="second_cell"><input type="text" name="product_name" value="'.$product_name.'"></input></td>
		</tr>
		<tr>
			<td class="first_cell">Price:</td>
			<td class="second_cell"><input type="text" name="price" value="'.$price.'">€</input></td>
		</tr>
		<tr>
			<td class="first_cell">Category:</td>
			<td class="second_cell">
			<select name="category">
				<option value="'.$category.'">'.$category.'</option>
				<option value="Camera">Camera</option>
				<option value="Lens">Lens</option>
				<option value="Accessory">Accessory</option>
			</select>			
			</td>
		</tr>
		<tr>
			<td class="first_cell">Image:</td>
			<td class="second_cell"><input type="file" name="image" id="file_field" /></input></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="details">'.$details.'</textarea></td>
		</tr>
		<tr>
			<input name="thisID" type="hidden" value="'.$targetID.'" />
			<td id="delete_button"><a href="index.php?slct=2&deleteid='.$targetID.'"><input type="button" class="button" value="delete" /></a></td>
			<td id="create_button"><input class="button" type="submit" name="edit" value="Edit"></td>
		</tr>
	</table>
	</form>';
	}
	else {
		// show notification if information is updated
		if (isset($_SESSION['updated'])) {
			echo '<div id="updated_notification"><b>Product updated!</b></div>';
			unset($_SESSION['updated']);
		}
		// THIS ALLOWS INVENTORY LIST TO BE PRINTED
		$product_list = "";
		$sql = mysql_query("SELECT * FROM products ORDER BY category, date_added");
		$productCount = mysql_num_rows($sql);
		
		// CHECKS IF PRODUCT AMOUNT IS GREATE THAN 0, IF NOT A MESSAGE IS ECHOED
		if ($productCount > 0) {
			// TABLE HEADERS
			$product_list .= "
			<div class=\"tablelist\">
			<table border=\"1\">
			<tr>
				<th>ID</td>
				<th>Product</td>
				<th>Category</td>
				<th>Price</td>
				<th>Created</td>
			</tr>";
				
		// TABLE DATA
		while($row = mysql_fetch_array($sql)){
			$id = $row["id"];
			$product_name = $row["product_name"];
			$price = $row["price"];
			$category = $row["category"];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			$product_list .= "
			<tr>
				<td class=\"table_id\">$id</td>
				<td class=\"table_product\"><a href=\"index.php?slct=2&imageid=$id\">$product_name</a></td>
				<td class=\"table_price\">$category</td>
				<td class=\"table_price\">$price €</td>
				<td class=\"table_created_on\">$date_added</td>
			</tr>";
		}
		
		// END TABLE
		$product_list .= "</table></div>";
	}
	else {
		$product_list = "You have no products listed in your store yet";
	}
	
	// ALLOWS ITEM TO BE DELETED
	if (isset($_GET['deleteid'])) {
		echo 'Do you really want to delete product with ID of ' . $_GET['deleteid'] . '? <a href="index.php?slct=2&yesdelete=' . $_GET['deleteid'] . '">Yes</a> | <a href="index.php?slct=2">No</a>';
		exit();
	}

	if (isset($_GET['yesdelete'])) {
		$id_to_delete = preg_replace('#[^0-9]#i', '',$_GET['yesdelete']);
		$sql = mysql_query("DELETE FROM products WHERE id='$id_to_delete' LIMIT 1") or die (mysql_error());

		// THIS UNLINKS THE PICTURE FROM ID
		$pictodelete = ("../inv_images/$id_to_delete.jpg");
		if (file_exists($pictodelete)) {
			unlink($pictodelete);
		}

		header("location: index.php?slct=2"); 
		exit();
	}
	echo "<h2>Inventory list</h2>";
	echo $product_list;
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