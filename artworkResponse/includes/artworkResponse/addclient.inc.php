

<?php
require 'dbh.inc.php';
/*
if(isset($_POST['projectoverview-addclient-submit'])){

*/	//Make Project

	

	$cname = $_POST['clientName'];
	$ccode = $_POST['clientCode'];
//Check if user already exists
		$sql = "SELECT clientCompany FROM clients WHERE clientCompanyCode=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "<p id='homepage-serverResponse' class='uk-alert-danger'>SQL Error</p>";
			exit();
		} else {
			mysqli_stmt_bind_param($stmt,"s",$ccode);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if($resultCheck > 0){
				echo "<p id='homepage-serverResponse' class='uk-alert-danger'>Client Exists</p>";
					exit();
			}else{
				$sql = "INSERT into clients (clientCompany,clientCompanyCode) VALUES (?,?)";
		$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					echo "<p id='homepage-serverResponse' class='uk-alert-danger'>SQL Error</p>";
					exit();
				}else {
					mysqli_stmt_bind_param($stmt,"ss",$cname,$ccode);
					mysqli_stmt_execute($stmt);
					echo "<p id='homepage-serverResponse' class='uk-alert-success'>Added Client</p>";
					exit();
				}
				
			}
		}
		
		

	
	//}

		mysqli_stmt_close($stmt);
		mysqli_close($conn);
