<?php
//Check if user did click submit the button from signup page
if(isset($_POST['signup-submit'])){
	//User did click the submit button
	require 'dbh.inc.php';

	$email = $_POST['mailuid'];
	$pwd = $_POST['pwd'];
	$firstname = $_POST['fname'];
	$lastname = $_POST['lname'];
	$pwd_repeat = $_POST['pwd-verify'];

	//If any of the feilds are empty then return the user to the form if they passed the html5 verification.
	if(empty($email) || empty($pwd) || empty($firstname) || empty($lastname) || empty($pwd_repeat)){
		header("Location: ../../signup.php?error=emptyfeilds&mailuid=".$email."&fname=".$firstname."&lname=".$lastname);
		exit();
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../../signup.php?error=wrongmail&fname=".$firstname."&lname=".$lastname);
		exit();
	}else{
		//Check if user already exists
		$sql = "SELECT emailUsers FROM users WHERE emailUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../signup.php?error=sqlerror");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt,"s",$email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if($resultCheck > 0){
				header("Location: ../../signup.php?error=userexists&fname=".$firstname."&lname=".$lastname);
				exit();
			}else{

				$sql = "INSERT into users (fName,lName,emailUsers,pwdUsers,appovedUser) VALUES (?,?,?,?,?)";
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					header("Location: ../../signup.php?error=sqlerror");
					exit();
				}else {
					$pwdhash = password_hash($pwd, PASSWORD_DEFAULT);
					$approvedValue = 0;
					mysqli_stmt_bind_param($stmt,"ssssi",$firstname,$lastname,$email,$pwdhash,$approvedValue);
					mysqli_stmt_execute($stmt);
					header("Location: ../../index.php?signup=sucess");
					exit();
				}
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);



}
else{
	header("Location: ../../signup.php");
	exit();
}