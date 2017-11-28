<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in(); ?>
<?php
	$product_id = $_GET['product_id'];

	$query = "DELETE FROM products WHERE id = {$product_id}";
	$result = mysqli_query($conn, $query);
	confirm_query($result);
	if ($result) {
		redirect_to("inventory.php");
	}
?>
<?php
if (isset ($conn)){
  mysqli_close($conn);
}
?>