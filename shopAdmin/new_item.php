<?php
require_once "check_login.php";	
?>

<form action="./index.php?slct=1" method="post" enctype="multipart/form-data">
	<h2>Add New Inventory Item</h2>
	<table id="create_table">
		<tr>
			<td class="first_cell">Product name:</td>
			<td class="second_cell"><input type="text" name="product_name"></input></td>
		</tr>
		<tr>
			<td class="first_cell">Price:</td>
			<td class="second_cell"><input type="text" name="price">€</input></td>
		</tr>
		<tr>
			<td class="first_cell">Category:</td>
			<td class="second_cell">
			<select name="category">
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
			<td colspan="2"><textarea name="details">Description...</textarea></td>
		</tr>
		<tr>
			<td><input type="hidden" id="created" name="created" value="0"></td>
			<td id="create_button"><input class="button" type="submit" name="create" value="Create"></td>
		</tr>
	</table>
</form>

<script type="text/javascript">
       	// push effect after ticket created
        if (document.getElementById('item_created'))
        {
          var element = document.getElementById('item_created');
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
          document.getElementById('created').value="1";
        }
        if (document.getElementById('item_created2')) document.getElementById('created').value="1";
</script>
