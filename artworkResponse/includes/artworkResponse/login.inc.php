<?php
//Check if user did click submit the button from signup page
if(isset($_POST['login-submit'])){
	//User did click the submit button
	require 'dbh.inc.php';

	$email = $_POST['mailuid'];
	$pwd = $_POST['pwd'];

	if(empty($email) || empty($pwd)){
		header("Location: ../../index.php?error=emptyfeilds");
		exit();
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../../index.php?error=wrongmail");
		exit();
	}else{
		//Check if user already exists
		$sql = "SELECT * FROM users WHERE emailUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt,"s",$email);
			mysqli_stmt_execute($stmt);
			
			$resultCheck = mysqli_stmt_get_result($stmt);
			if($row = mysqli_fetch_assoc($resultCheck)){
				$pwdcheck = password_verify($pwd, $row['pwdUsers']);
				if($pwdcheck == false){
					header("Location: ../../index.php?error=wrongpassword");
					exit();
				}else if($pwdcheck == true){
					if($row['appovedUser'] == 0){
						header("Location: ../../index.php?error=usernotapproved");
						exit();
					}else if($row['appovedUser'] == 1){
						session_start();
						$_SESSION['userID'] = $row['idUsers'];
						$_SESSION['fName'] = $row['fName'];
						$_SESSION['userEmail'] = $row['emailUsers'];
						header("Location: ../../dashboard.php");
						exit();
					}else{
						header("Location: ../../index.php?error=usernotapproved");
						exit();
					}
				}else{
					header("Location: ../../index.php?error=wrongpassword");
					exit();
				}
			}else{
				header("Location: ../../index.php?error=nouser");
				exit();
			}
		}
	}


}else{
	header("Location: ../../index.php");
	exit();
}