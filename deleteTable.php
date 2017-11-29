<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in(); ?>
<?php
	$table_id = $_GET['table_id'];

	$query = "DELETE FROM tables WHERE id = '{$table_id}'";
	$result = mysqli_query($conn, $query);
	confirm_query($result);
	if ($result) {
		redirect_to("dashboard.php");
	}
?>
<?php
if (isset ($conn)){
  mysqli_close($conn);
}
?>