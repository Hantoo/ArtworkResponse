<?php
require 'dbh.inc.php';

if(isset($_POST['user-delete']) || isset($_POST['user-approve'])){

	if(isset($_POST['user-delete'])){
		session_start();
		$userID = $_POST['user-delete'];
		$loggedinID = $_SESSION['userID'];
		$sql = "DELETE FROM users WHERE idUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../users.php?error=sqlerror");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt,"i",$userID);
			mysqli_stmt_execute($stmt);
			
			/*$resultCheck = mysqli_stmt_get_result($stmt);
			if($resultCheck == TRUE){
				header("Location: ../../users.php?success=delete");
				exit();
			}else{
				header("Location: ../../users.php?error=delete");
				exit();
			}*/
			if($loggedinID == $userID){
				require 'logout.inc.php';
				exit();
			}
			header("Location: ../../users.php?".$loggedinID."_".$userID);
			exit();
		}
	}
	if(isset($_POST['user-approve'])){
		$userID = $_POST['user-approve'];
		$sql = "UPDATE users SET appovedUser = 1 WHERE idUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../users.php?error=sqlerror");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt,"i",$userID);
			mysqli_stmt_execute($stmt);
			
			/*$resultCheck = mysqli_stmt_get_result($stmt);
			if($resultCheck == TRUE){
				header("Location: ../../users.php?success=approve");
				exit();
			}else{
				header("Location: ../../users.php?error=".$resultCheck);
				exit();
			}*/
			header("Location: ../../users.php");
			exit();
		}
	}

	}else{
//Generate users table
		
		$sql = "SELECT * FROM users";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../users.php?error=sqlerror");
			exit();
		} else {


			$result = mysqli_query($conn, $sql);
			echo "<p>".$result->num_rows." Users Found</p>";
			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
			  	
			  	if($row['appovedUser'] == True){ $approvedUserVar = "True"; }else{ $approvedUserVar = "False";};
			  
if($row['appovedUser'] == True){ $actionButtons = " <button class='uk-button-danger' name='user-delete' value='".$row['idUsers']."'>Delete User</button>"; }else{ $actionButtons= "<button class='uk-button-danger' name='user-delete' value='".$row['idUsers']."'>Delete User</button>
				            <button class='uk-button-primary' name='user-approve' value='".$row['idUsers']."'>Approve User</button>";};

			    echo "<tr id='users-row'>
				            <td>".$row['idUsers']."</td>
				            <td>".$row['fName']."</td>
				            <td>".$row['lName']."</td>
				            <td>".$row['emailUsers']."</td>
				            <td>".$approvedUserVar."</td>

				            <td><form action='includes/artworkResponse/usertable.inc.php' method='post'>
				            ".$actionButtons."
				            </form></td>
				        </tr>
				        ";
			  }
			} else {
			  echo "0 results";
			}
			
		}
	}

		mysqli_stmt_close($stmt);
		mysqli_close($conn);
