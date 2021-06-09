<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html>

<head>
 <tittle>HOME</tittle>
 <link rel="stylesheet" href="CSS/styles.css" type="text/css" />
</head>
<body>
  <h1>BIENVENIDO</h1>
</body>   

</html>