<?php
require 'dbh.inc.php';

if(isset($_POST['projectoverview-addproject-submit'])){

	//Make Project
	$pname = $_POST['projectName'];
	$pcode = $_POST['projectCode'];
	$pdesc = $_POST['projectDesc'];
	$pgithub = $_POST['projectGitLink'];
	$plongitude = $_POST['projectLocationLongitude'];
	$platitude = $_POST['projectLocationLatitude'];
	$pstatus = $_POST['projectStatustext'];
	$pclient = $_POST['projectClient'];
	$pmaintanceperiod = $_POST['projectMainenancePeriod'];
	$pbg = $_POST['projectBGLink'];
	/*$pteammembers = $_POST['projectTeamMembers'];*/

		$sql = "INSERT into projects (projectCode,name,status) VALUES (?,?,?)";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../projectOverview.php?error=sqlerror");
			exit();
		}else {
			
			mysqli_stmt_bind_param($stmt,"sss",$pcode,$pname,$pstatus);
			mysqli_stmt_execute($stmt);
			
			$sql = 'SELECT * FROM projects WHERE projectCode=\''.$pcode.'\'';
         	$result = mysqli_query($conn, $sql);
         	$IDnum = -1;
         	if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
               $IDnum = $row["unqiueID"];
	            }
	         } else {
	            header("Location: ../../projectOverview.php?error=sqlerror&desc=NoUnquieIDFound");
				exit();
	         }
			

			$sql = "INSERT into projectdetails (projectID,clientName,customDataTableName,githubURL,maintancePeriod, projectDescription, projectLatitude, projectLongitude, projectBackgroundURL,uniqueProjectKey) VALUES (?,?,?,?,?,?,?,?,?,?)";
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("Location: ../../projectOverview.php?error=sqlerror");
				exit();
			}else {
				
				$dattablename = $pcode."_data";
				$keyGen = keygen(20);
				mysqli_stmt_bind_param($stmt,"isssisddss",$IDnum,$pclient,$dattablename,$pgithub,$pmaintanceperiod,$pdesc,$platitude,$plongitude,$pbg,$keyGen);
				mysqli_stmt_execute($stmt);
				
				$sql = "CREATE TABLE ".$dattablename."(entryID int(12) PRIMARY KEY AUTO_INCREMENT NOT NULL, entryDateTime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";
			
				if(!mysqli_stmt_prepare($stmt, $sql)){
					header("Location: ../../projectOverview.php?error=sqlerror");
					exit();
				}else {
					
					mysqli_stmt_execute($stmt);
					header("Location: ../../projectOverview.php?sucess=true");
					exit();
				}
				
			}

		}
		
		//If user is updating their project from within the project view page
	}else if(isset($_POST['projectoverview-updateproject-submit'])){

	$pid = $_POST['projectID'];
	$pname = $_POST['projectName'];
	$pcode = $_POST['projectCode'];
	$pdesc = $_POST['projectDesc'];
	$pgithub = $_POST['projectGitLink'];
	$plongitude = $_POST['projectLocationLongitude'];
	$platitude = $_POST['projectLocationLatitude'];
	$pstatus = $_POST['projectStatustext'];
	$pclient = $_POST['projectClient'];
	$pmaintanceperiod = $_POST['projectMainenancePeriod'];
	$pbg = $_POST['projectBGLink'];
	/*$pteammembers = $_POST['projectTeamMembers'];*/

		$sql = "UPDATE projects SET name=?,status=? WHERE unqiueID=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../projectview.php?project=".$pid."&error=sqlerror1");
			exit();
		}else {
			
			mysqli_stmt_bind_param($stmt,"ssi",$pname,$pstatus, $pid);
			mysqli_stmt_execute($stmt);
						
			$sql = "UPDATE projectdetails SET clientName=?,githubURL=?,maintancePeriod=?, projectDescription=?, projectLatitude=?, projectLongitude=?, projectBackgroundURL=? WHERE projectID=?";
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("Location: ../../projectview.php?project=".$pid."&error=sqlerror2");
				exit();
			}else {
				
				$dattablename = $pcode."_data";
				mysqli_stmt_bind_param($stmt,"ssisddsi",$pclient,$pgithub,$pmaintanceperiod,$pdesc,$platitude,$plongitude,$pbg,$pid);
				mysqli_stmt_execute($stmt);
				header("Location: ../../projectview.php?project=".$pid."&sucess=true");
				exit();
			}

		}

	}else if(isset($_POST['projectoverview-updateproject-delete'])){
		$pid1 = $_POST['projectID'];
		$pid2 = $_POST['projectID'];
		$stmt = mysqli_stmt_init($conn);
		$sql = "DELETE FROM projectdetails WHERE projectID=?";
		
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../projectview.php?project=".$pid1."&error=sqlerror1");
			exit();
		}else {
			
			mysqli_stmt_bind_param($stmt,"i", $pid1);
			mysqli_stmt_execute($stmt);

			$sql = "DELETE FROM projects WHERE unqiueID=?";
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("Location: ../../projectview.php?project=".$pid2."&error=sqlerror2");
				exit();
			}else {
				
				mysqli_stmt_bind_param($stmt,"i", $pid2);
				mysqli_stmt_execute($stmt);
				header("Location: ../../projectOverview.php?error=projectDeleted1-".$pid2);
				exit();
			}
			header("Location: ../../projectOverview.php?error=projectDeleted2-".$pid2);
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


function keygen($length=10)
{
	$key = '';
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	
   	$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

   	for($i=0; $i<$length; $i++)
	{
   	    $key .= $inputs{mt_rand(0,61)};
	}
	return $key;
}