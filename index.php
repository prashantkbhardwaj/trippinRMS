<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php");?>
<?php require_once("includes/validation_functions.php"); ?>
<?php
if (logged_in()) {
    redirect_to ("dashboard.php");
}
?>
<?php
$username = "";
if (isset($_POST['submit'])) {

    $required_fields = array("username", "password");
    validate_presence($required_fields);
    
    if (empty($errors)) {

        $username = $_POST['username'];     
        $password = $_POST['password'];
        $found_user = attempt_login($username, $password);

        if ($found_user) {

            $_SESSION["user_id"] = $found_user["id"];
            $_SESSION["username"] = $found_user["username"];
            redirect_to("dashboard.php");
        } else {
            $_SESSION["message"] = "Username/password not found.";
        }
    }
} else {

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>IDEA Inventory | Login</title>
        <meta name="description" content="idea inventory" />
        <meta name="keywords" content="Idea Inventory" />
        <meta name="author" content="Shirisha udatewar" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="js/modernizr.custom.63321.js"></script>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<style>
			@import url(http://fonts.googleapis.com/css?family=Ubuntu:400,700);
			body {
				background: #563c55 url(images/admin_bg.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
			.container > header h1,
			.container > header h2 {
				color: #fff;
				text-shadow: 0 1px 1px rgba(0,0,0,0.7);
			}
		</style>
    </head>
    <body>
        <div class="container">	
			
			<header>
			
				<h1>IDEA<strong> Inventory</strong></h1>
				<h2>Enter your details</h2>

				<div class="support-note">
					<span class="note-ie">Sorry, only modern browsers.</span>
				</div>
				
			</header>
			
			<section class="main">
				<form class="form-3" method="post" action="index.php">
				    <p class="clearfix">
				        <label for="login">Username</label>
				        <input type="text" name="username" required id="login" placeholder="Username">
				    </p>
				    <p class="clearfix">
				        <label for="password">Password</label>
				        <input type="password" name="password" required id="password" placeholder="Password"> 
				    </p>				   
				    <p class="clearfix">
				       <center> <input type="submit" name="submit" value="Sign in"></center>
				    </p>       
				</form>​
			</section>
			
        </div>
    </body>
</html>
<?php
if (isset ($conn)){
  mysqli_close($conn);
}
?>
