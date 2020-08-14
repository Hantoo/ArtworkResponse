<?php
require 'dbh.inc.php';
//Check if user did click submit the button from dashboard page
if(isset($_POST['refreshPins'])){
	//refresh all pins
	
/*	
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt, $sql)){
		header("Location: ../../signup.php?error=sqlerror");
		exit();
	} else {
		mysqli_stmt_execute($stmt);

	}


*/



	//Go through every project within the projects table, check if it's data table has had an entry within the last 15 minutes
	//If it has then set as being online, else set as being offline. This changes colours of pins 
	$sql = "SELECT * FROM projects";
 	$result = mysqli_query($conn, $sql);
 	if (mysqli_num_rows($result) > 0) {
	    while($row = mysqli_fetch_assoc($result)) {
	       $code = $row["projectCode"];
			$id = $row["unqiueID"];
			$name = $row["name"];
	       $sql2 = "SELECT entryDateTime FROM ".$code."_data ORDER BY entryID DESC LIMIT 1;";
		 	$result2 = mysqli_query($conn, $sql2);
		 	if(!$result2 ||  mysqli_num_rows($result2) == 0 ){
		 		$sql3 = "UPDATE projects SET online=0 WHERE unqiueID=".$id.";";
						$stmt = mysqli_stmt_init($conn);
						if(!mysqli_stmt_prepare($stmt, $sql3)){
							header("Location: ../../dashboard.php?error=sqlerror&selection=2");
							exit();
						}else{
							mysqli_stmt_execute($stmt);
						}
		 		continue;
		 	}
		 	if(!(is_null($result2))){
		 	if (mysqli_num_rows($result2) > 0) {
			    while($row2 = mysqli_fetch_assoc($result2)) {
			       $timeofEntry = $row2["entryDateTime"];

			        $now    = time();
					$target = strtotime($timeofEntry);
					$diff   = $now - $target;

					// 15 minutes = 15*60 seconds = 900
					if ($diff <= 900) {
						//Under 15Mins
						$sql3 = "UPDATE projects SET online=1 WHERE unqiueID=".$id.";";
						$stmt = mysqli_stmt_init($conn);
						if(!mysqli_stmt_prepare($stmt, $sql3)){
							  header("Location: ../../dashboard.php?error=sqlerror&selection=1");
							  exit();
						}else{
							//echo $name." set to 1";
							mysqli_stmt_execute($stmt);
						}
					    //$seconds = $diff;
					} else {
						//Over 15Mins
						$sql3 = "UPDATE projects SET online=0 WHERE unqiueID=".$id.";";
						$stmt = mysqli_stmt_init($conn);
						if(!mysqli_stmt_prepare($stmt, $sql3)){
							header("Location: ../../dashboard.php?error=sqlerror&selection=2");
							exit();
						}else{
							//echo $name." set to 0";
							mysqli_stmt_execute($stmt);
						}
					    //$seconds = $diff;
					}
			       
			     }
		     }
		 }else{
		 	$sql3 = "UPDATE projects SET online=0 WHERE unqiueID=".$id.";";
						$stmt = mysqli_stmt_init($conn);
						if(!mysqli_stmt_prepare($stmt, $sql3)){
							header("Location: ../../dashboard.php?error=sqlerror&selection=2");
							exit();
						}else{
							mysqli_stmt_execute($stmt);
						}
		 }

	     }
	     header("Location: ../../dashboard.php");
							exit();
     } else {
        header("Location: ../../dashboard.php?error=sqlerror&desc=NoUnquieIDFound");
		exit();
     }

}