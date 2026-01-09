<?php 

header('Content-Type: text/html; charset=utf-8');
$servername="localhost:3307";
$username="root";
$pass="";
$basename="id21784316_zerjav";
$dbc = mysqli_connect($servername, $username, $pass, $basename) or die('Error connecting to MySQL server.'.mysqli_connect_error()); 
mysqli_set_charset($dbc, "utf8"); // Check connection 
if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
  }

