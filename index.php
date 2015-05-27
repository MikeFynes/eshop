<?php
session_start();

// GENERATE LATEST PRODUCTS LIST FOR THE FRONT PAGE
include "scripts/connect_to_mysql.php"; 
$dynamicList = "";
$sql = mysql_query("SELECT * FROM products ORDER BY date_added DESC LIMIT 6");
$posterCount = iterator_count($fi)
if ($posterCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
		$id = $row["id"];
		$product_name = $row["product_name"];
		$price = $row["price"];
		$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		$dynamicList .= '
		 <div id="block_container">
			<div class="block">
			 <a href="index.php?id=' . $id . '"><img src="inv_images/' . $id . '.jpg" alt="' . $product_name . '"/></a>
			 <p><b>' . $product_name . '</b></p>
			 <p>' . $price . ' €</p>
            </div>
		 </div>
		';
    }
} else {
	$dynamicList = "There is nothing in the store yet";
	mysql_close();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
      	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title> CameraShop </title>
        <link rel=stylesheet href="style.css" type="text/css">

</head>

<body>

<div id="main_container">
	<div id="logo">
		<a href="index.php"><img class="logo" src="design.jpg" alt="image"/></a>
	</div>

	<div id="header">
		<div class="header_links">
			<span>Cart: <?php echo count($_SESSION["cart_array"]) ?></span>
			<a href="index.php?showcart=1">Show Cart</a>
		</div>
	</div>

	<div id="content_wrapper">
		<div id="content">
		<?php
			// DISPLAY CONTENT ACCORDING TO SELECTION
			if (isset($_GET['ctgr'])) include 'category.php';
			else if (isset($_GET['id'])) include 'product.php';
			else if (isset($_GET['showcart'])) {
				preg_replace('#[^0-9]#i', '',$_GET['showcart']);
				if ($_GET['showcart'] == 1) include 'cart.php';
				else {
					header("location: index.php"); 
					exit();
				}
			}
			// IF NOTHING SELECTED, DISPLAY LATEST PRODUCTS
			else {
				if (isset($_SESSION["order_confirmed"])) {
					echo '
						<div id="topbar"><h2>Our latest products!</h2></div>
						<div class="innertube">
						Your order has been confirmed! Check your email.
						</div>
					';
					unset($_SESSION["order_confirmed"]);
				}
				else {
				echo '
					<div id="topbar"><h2>Our latest products!</h2></div>
					'.$dynamicList.'
					<div class="innertube">
					</div>
				';
				}
			}
		?>
		</div>
	</div>

	<div id="navigation">
		<div class="innertube">
			<h1>CameraShop</h1>				         
			<ul class="pink">
				<li><a href="index.php" title="Home" ><span>Home</span></a></li>
				<li><a href="index.php?ctgr=1" title="Cameras" ><span>Cameras</span></a></li>
				<li><a href="index.php?ctgr=2" title="Lenses"><span>Lenses</span></a></li>
				<li><a href="index.php?ctgr=3" title="Accessories"><span>Accessories</span></a></li>
			</ul>
		</div>
	</div>

	<div id="footer">
		CameraShop
	</div>

</body>
</html>
