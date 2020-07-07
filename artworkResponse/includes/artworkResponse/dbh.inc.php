<?php
//Database Handler

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "artworkresponse";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
if(!$conn){
	die("Connection To Server Failed: ".mysqli_connect_error());
}