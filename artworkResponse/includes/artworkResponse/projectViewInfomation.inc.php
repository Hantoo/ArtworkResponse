<?php

	$PROJECT_NAME = "";
	require 'dbh.inc.php';
	$projectID = $_GET['project'];
	$sql = "SELECT * FROM projects WHERE unqiueID=".$projectID;
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "<option>SQL ERROR</option>";
		} else {


			$result = mysqli_query($conn, $sql);
			//echo "<p>".$result->num_rows." projects Found</p>";
			if ($result->num_rows > 0) {
			  // output data of each row
			$echotext = "";
			  while($row = $result->fetch_assoc()) {
			  	
			  	$PROJECT_NAME = $row['name'];
			  	
			    $echotext = $echotext."<p>".$row['name']." ".$row['name']." ".$row['projectLatitude']."</p>";
				         
				    
			  }
			   echo $echotext;
			} else {
			  echo "<option>0 TeamMembers</option>";
			}
			
		}
