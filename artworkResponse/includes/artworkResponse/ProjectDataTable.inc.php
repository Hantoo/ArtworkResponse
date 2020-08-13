<?php
require 'dbh.inc.php';

if(isset($_POST['sqlstatement'])){

	$sql = $_POST['sqlstatement'];
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt, $sql)){
		header("Location: ../../projectOverview.php?error=sqlerror&command=".$sql);
		exit();
	}else {
		
		
		mysqli_stmt_execute($stmt);
		header("Location: ". $_SERVER['HTTP_REFERER']);
		exit();
	}
	

}else{
	header("Location: ../../projectOverview.php?failed=true");
	exit();
}