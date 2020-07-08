<?php
require 'dbh.inc.php';

if(isset($_POST['projectoverview-addproject-submit'])){

	//Make Project

	

	$pname = $_POST['projectName'];
	$pcode = $_POST['projectCode'];
	$pstatus = $_POST['projectStatustext'];

		$sql = "INSERT into projects (projectCode,name,status) VALUES (?,?,?)";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../signup.php?error=sqlerror");
			exit();
		}else {
			$pwdhash = password_hash($pwd, PASSWORD_DEFAULT);
			$approvedValue = 0;
			mysqli_stmt_bind_param($stmt,"sss",$pname,$pcode,$pstatus);
			mysqli_stmt_execute($stmt);
			header("Location: ../../projectOverview.php?project=addsucess");
			exit();
		}
		

	}else if(isset($_POST['projectOverview-view'])){


		header("Location: ../../projectview.php?project=".$_POST['projectOverview-view']);
		exit();

	}else{
//Generate users table
		
		$sql = "SELECT * FROM projects";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../projectOverview.php?error=sqlerror");
			exit();
		} else {


			$result = mysqli_query($conn, $sql);
			echo "<p>".$result->num_rows." projects Found</p>";
			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
			  	
			  	
			  	if($row['online'] == 0){$onlinestatus = "<span id='projectOverview-status-offline'><i class='fa fa-circle' aria-hidden='true'></i></span>";}else{$onlinestatus = "<span id='projectOverview-status-online'><i class='rotating fa fa-circle-o-notch' aria-hidden='true'></i></span>";};
			    echo "<tr id='users-row'>
			    			<td>".$onlinestatus."</td>
				            <td>".$row['unqiueID']."</td>
				            <td>".$row['projectCode']."</td>
				            <td>".$row['name']."</td>
				            <td>".$row['status']."</td>
				            <td>".$row['creationDate']."</td>
				            <td><form action='includes/artworkResponse/projectstable.inc.php' method='post'><button class='uk-button-primary' name='projectOverview-view' type='submit' value='".$row['unqiueID']."'>View</button></form></td>
				        </tr>
				        ";
				        /*<td><form action='includes/artworkResponse/usertable.inc.php' method='post'>
				            ".$actionButtons."
				            </form></td>*/
			  }
			} else {
			  echo "0 results";
			}
			
		}
	}

		mysqli_stmt_close($stmt);
		mysqli_close($conn);
