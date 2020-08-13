
	<?php
	require 'includes/artworkResponse/dbh.inc.php';
	if(isset($_GET['Ping'])){
		//If project is pinging then return pong with time that server generated the message.
	echo date("h:i:sa");
	}elseif(isset($_GET['dataTable'])){

		//Else expect the project to be wanting to add to the database.
		$tableName = $_GET['tableName'];
		$variableAmount = $_GET['numOfVariables'];
		$names = "";
		$nameinserts = "";
		$datainserts = "";
		for($x = 0; $x < $variableAmount; $x++){
			$columnName = $_GET['columnName'.$x];
			$columnData = $_GET['columnData'.$x];

			if($x == 0){
				$names = $names.$columnName;
				$nameinserts = $nameinserts."?";
				$datainserts = $datainserts.$columnData;
			}else{
				$names = $names.",".$columnName;
				$nameinserts = $nameinserts.","."?";
				$datainserts = $datainserts.",".$columnData;
			}
			
		}

		$sql = 'SELECT uniqueProjectKey FROM projectdetails WHERE customDataTableName = N\''.$tableName.'\'';
     	$result_data_key = mysqli_query($conn, $sql);
     	while ($row = mysqli_fetch_assoc($result_data_key)) { 
		     $key = $row['uniqueProjectKey']; 
		}

		if($key !=  $_GET['unqiueKey']){
			echo "Incorrect key";
			exit();
		}

		$sql = "INSERT into ".$tableName." (".$names.") VALUES (".$datainserts.")";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo"SQL Error - Ensure Command Syntax Is Correct And Value Data Types Are Correct";
			exit();
		}else {
			mysqli_stmt_execute($stmt);
		}
	}elseif(isset($_GET['getTableInfomation'])){
		
		
			echo "<p>table Infomation</p>";
		
	}elseif(isset($_GET['getProjects'])){

		$sql = 'SELECT * FROM projects';
     	$result_data = mysqli_query($conn, $sql);
     	$sqlColumnNames = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N\'projects\'';
     	$result_columnNames = mysqli_query($conn, $sqlColumnNames);
     	
     	while ($row = mysqli_fetch_assoc($result_columnNames)) { 
		     $column_names[] = $row; 
		} 

		
     	if (mysqli_num_rows($result_columnNames) > 0) {
	     	if (mysqli_num_rows($result_data) > 0) {
	     		echo "<table><tr>";
		     		foreach($column_names as $row) {
					    echo "<th>".$row['COLUMN_NAME']."</th>";
					}
	     		
	     		
	     		echo "</tr>";
	        	while($rowData = mysqli_fetch_assoc($result_data)) {
	          		echo "<tr>";
	          		
	          		foreach($column_names as $rowColName) {
	          			$colName = strval($rowColName['COLUMN_NAME']);
					    echo "<td>".$rowData[$colName]."</td>";
					}
		     		
	     			echo "</tr>";
	     			}
	            }
	            echo "</table>";
	         }
	     	
     	}elseif(isset($_GET['getProject'])){

		
     	$sqlColumnNames = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N\''.$_GET['tableName'].'\'';
     	$result_columnNames = mysqli_query($conn, $sqlColumnNames);
     	
     	while ($row = mysqli_fetch_assoc($result_columnNames)) { 
		     $column_names[] = $row; 
		} 

		
     	if (mysqli_num_rows($result_columnNames) > 0) {
	     	
	     		echo "<table><tr>";
		     		foreach($column_names as $row) {
					    echo "<th>".$row['COLUMN_NAME']."</th>";
					}
	     		
	     		
	     		echo "</tr><tr>";
	        	foreach($column_names as $row) {
					    echo "<td>".$row['DATA_TYPE']."</td>";
					}
	     			echo "</tr></table>";
	            }
	            
	         
	     	
     	}else{
			echo "<p>Connected To artworkResponse</p>";
		}
		
	



	?>

