<?php

	require 'dbh.inc.php';



	$sql = "SELECT * FROM clients";
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
			  	
			  
			  	
			    	$echotext = $echotext."<option value='".$row['clientCompanyCode']."'>".$row['clientCompanyCode']." - ".$row['clientCompany']."</option>";
			
				         
				    
			  }
			   echo $echotext;
			} else {
			  echo "<option>0 Clients</option>";
			}
			
		}
