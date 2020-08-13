<?php
//Database Handler

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "artworkresponse";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
if(!$conn){
	die("Connection To Server Failed: ".mysqli_connect_error());
}

//If tables don't exist create them
$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '".$dbName."' AND table_name = 'projectdetails' LIMIT 1;";
$stmt = mysqli_stmt_init($conn);
$result = mysqli_query($conn, $sql); 
if ($result) 
{ 
	$row = mysqli_num_rows($result);
}
if(!$row){

	$sql = "CREATE TABLE `projectdetails` ( `projectID` int(6) DEFAULT NULL, `projectBackgroundURL` longtext DEFAULT NULL, `projectLongitude` float DEFAULT NULL, `projectLatitude` float DEFAULT NULL, `projectDescription` mediumtext DEFAULT NULL, `projectInstallationDate` date DEFAULT NULL, `clientName` tinytext DEFAULT NULL, `maintancePeriod` int(11) DEFAULT NULL, `customDataTableName` mediumtext DEFAULT NULL, `githubURL` mediumtext NOT NULL, `uniqueProjectKey` varchar(20) NOT NULL, KEY `projectID` (`projectID`), CONSTRAINT `projectdetails_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`unqiueID`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror&section=2");
			exit();
		}else {
			mysqli_stmt_execute($stmt);

    	}

    	$sql = "CREATE TABLE `clients` ( `clientCompany` tinytext DEFAULT NULL, `clientCompanyCode` varchar(50) NOT NULL, PRIMARY KEY (`clientCompanyCode`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror&section=3");
			exit();
		}else {
			mysqli_stmt_execute($stmt);

    	}

    	$sql = "CREATE TABLE `projects` ( `unqiueID` int(11) NOT NULL AUTO_INCREMENT, `projectCode` tinytext DEFAULT NULL, `name` tinytext NOT NULL, `status` tinytext NOT NULL, `creationDate` timestamp NOT NULL DEFAULT current_timestamp(), `online` bit(1) NOT NULL DEFAULT b'0', PRIMARY KEY (`unqiueID`) ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror&section=4");
			exit();
		}else {
			mysqli_stmt_execute($stmt);

    	}

    	$sql = "CREATE TABLE `users` ( `idUsers` int(11) NOT NULL AUTO_INCREMENT, `fName` tinytext NOT NULL, `lName` tinytext NOT NULL, `emailUsers` tinytext NOT NULL, `pwdUsers` longtext NOT NULL, `appovedUser` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`idUsers`) ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror&section=5");
			exit();
		}else {
			mysqli_stmt_execute($stmt);

    	}

    	$pwd = "root";
    	$pwdhash = password_hash($pwd, PASSWORD_DEFAULT);
    	$sql = "INSERT INTO `users` (fName, lName, emailUsers, pwdUsers, appovedUser) values ('Root', 'Root', 'root@root.com', '".$pwdhash."', 1)";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../../index.php?error=sqlerror&section=6");
			exit();
		}else {
			mysqli_stmt_execute($stmt);

    	}


}
