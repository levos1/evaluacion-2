<?php
$hn= 'localhost';
$db ='publications';
$un ='root';
$pw ='joaquin12';


if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
?>