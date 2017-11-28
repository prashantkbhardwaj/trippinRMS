<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in(); ?>
<?php
	$order_id = $_GET['order_id'];
	$table_id = $_GET['table_id'];

	$query = "DELETE FROM orders WHERE order_id = '{$order_id}' AND table_id = '{$table_id}'";
	$result = mysqli_query($conn, $query);
	confirm_query($result);
	$query_update = "UPDATE tables SET current_bill = '0' WHERE id = '{$table_id}' AND current_bill = '{$order_id}'";
	$result_update = mysqli_query($conn, $query_update);
	confirm_query($result_update);
	if ($result && $result_update) {
		redirect_to("dashboard.php");
	}
?>
<?php
if (isset ($conn)){
  mysqli_close($conn);
}
?>