<?php 
	require "header.php";


 if(isset($_SESSION['userID'])){ 


	require "sidebarMenu.php";

	//require "includes/artworkResponse/projectViewInfomation.inc.php";


	$PROJECT_NAME = "";
	$PROJECT_CODE = "";
	$PROJECT_ONLINE = 0;
	$PROJECT_STATUS = "";
	require 'includes/artworkResponse/dbh.inc.php';
	$projectID = $_GET['project'];
	$sql = "SELECT * FROM projects WHERE unqiueID=".$projectID;
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "<p>SQL ERROR1</p>";
		} else {


			$result = mysqli_query($conn, $sql);
			//echo "<p>".$result->num_rows." projects Found</p>";
			if ($result->num_rows > 0) {
			  // output data of each row
			$echotext = "";
			  while($row = $result->fetch_assoc()) {
			  	
			  	$PROJECT_NAME = $row['name'];
			  	$PROJECT_CODE = $row['projectCode'];
			    $PROJECT_ONLINE = $row['online'];
				$PROJECT_STATUS = $row['status'];

				    
			  }

			 	$sql = "SELECT * FROM projectdetails WHERE projectID=".$projectID;
				if(!mysqli_stmt_prepare($stmt, $sql)){
					echo "<p>SQL ERROR2</p>";
				} else {


					$result = mysqli_query($conn, $sql);
					//echo "<p>".$result->num_rows." projects Found</p>";
					if ($result->num_rows > 0) {
					  // output data of each row
					$echotext = "";
					  while($row = $result->fetch_assoc()) {
					  	
					  	$PROJECT_GITHUB = $row['githubURL'];
					  	$PROJECT_BG = $row['projectBackgroundURL'];
					  	if($PROJECT_BG == ""){
					  		$PROJECT_BG = "https://images.unsplash.com/photo-1531811027466-d90d527b4424?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1267&q=80";
					  	}
					    $PROJECT_ONLINE = $row['maintancePeriod'];
						$PROJECT_DESC =  $row['projectDescription'];
						$PROJECT_INSTALLDATE =  $row['projectInstallationDate'];
						$PROJECT_MAINT =  $row['maintancePeriod'];
						$PROJECT_NEXTMAINTANCEDATE = "";
						$PROJECT_DATATABLE = $row['customDataTableName'];
						$PROJECT_UNQIUEKEY = $row['uniqueProjectKey'];
						if($PROJECT_INSTALLDATE != ""){
							$begin =  date('Y-m-d',strtotime($PROJECT_INSTALLDATE));
							$begin_abs = $begin;
							$end = date("Y-m-d");

							
							$amountToAdd = 1;
							while ($begin < $end) {
							  /*$begin = strtotime("+".$PROJECT_MAINT." months", $begin);*/
							  $begin = date('Y-m-d',strtotime($begin." +".$PROJECT_MAINT." Months"));
							  $amountToAdd = $amountToAdd + 1;
							}
							$MonthsToAdd = $PROJECT_MAINT * $amountToAdd;
							$PROJECT_NEXTMAINTANCEDATE = date('Y-m-d',strtotime($begin_abs." +".$MonthsToAdd." Months"));
							$PROJECT_PREVMAINTANCEDATE = date('Y-m-d',strtotime($PROJECT_NEXTMAINTANCEDATE." -".$PROJECT_MAINT." Months"));

							
						}else{
							$PROJECT_INSTALLDATE = "No Installation Date Set";
							$PROJECT_NEXTMAINTANCEDATE = "";
							$PROJECT_PREVMAINTANCEDATE = "";
						}
						
						


						
						$PROJECT_LAT =  $row['projectLatitude'];
						$PROJECT_LONG =  $row['projectLongitude'];
						$PROJECT_CLIENT =  $row['clientName'];
						
						    
					  }





			   echo '<main id="dashboard-main" onload="loadMap()">
			   <div class="uk-card uk-card-default uk-card-body projectView-MainShadow uk-margin-auto" style="padding-top:0px !important; padding-left: 0px; padding-right: 0px;">
						<div id="projectHeaderImg" style="background-image:url(\''.$PROJECT_BG.'\'); background-size: cover;" ></div>
						<div id="centeredDiv" class="uk-margin-auto">
							<h2 id="projectView-Header"><span>['.$PROJECT_CODE.'] '.$PROJECT_NAME.'</span></h2>

						</div>
						
						<div id="centeredDiv" class="uk-margin-auto projectView-Desc ">
						<p >'.$PROJECT_DESC.'</p><a target="_blank" href="https://www.google.com/maps/@'.$PROJECT_LAT.','.$PROJECT_LONG.',20z"><img id="projectView-Map" src="" /></a>
						</div>
						</div>
						<br/>
						
						<div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-margin-auto">
						    <h3 class="uk-card-title">Project Infomation</h3>
						    <p>Installed On: '.$PROJECT_INSTALLDATE.'</p>
							<p>Next Maintenance Date: '.$PROJECT_NEXTMAINTANCEDATE.'</p>
							<p>Previous Maintenance Date: '.$PROJECT_PREVMAINTANCEDATE.'</p>
							
						</div>
						<br/>
						<br/>
						<div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-margin-auto">
						<a target="_blank" href="'.$PROJECT_GITHUB.'"><button class="uk-button uk-button-secondary"><i class="fa fa-github"></i> View On Github</button></a>
						<button class="uk-button uk-button-danger" href="#projectview-changeProject" uk-toggle onclick="setClientSelected(\''.$PROJECT_CLIENT.'\'); setMaintancePeriod('.$PROJECT_MAINT.'); setProjectStatus(\''.$PROJECT_STATUS.'\');">Edit Project Infomation</button>
						</div>
						<br />





						<!-- Modals -->






					<script>
					
					document.getElementById(\'projectView-Map\').src = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22%3A%22Point%22%2C%22coordinates%22%3A%5B'.$PROJECT_LONG.'%2C'.$PROJECT_LAT.'%5D%7D)/'.$PROJECT_LONG.','.$PROJECT_LAT.',14.25,0,60/"+Math.min(document.getElementsByClassName(\'projectView-Desc\')[0].offsetWidth,1280)+"x200?access_token="+mapAccessToken;
				

					</script>


							';
			} else {
			  echo "<option>No Infomation On Project</option>";
			}
		}
	}
}
			
?>	

<script>
$(function () {
$('#projectoverview-addproject-form-addClient').bind('submit', function () {
$.ajax({
type: 'post',
url: 'includes/artworkResponse/addclient.inc.php',
data: $('#projectoverview-addproject-form-addClient').serialize(),
success: function(data) {
			document.getElementById("projectoverview-addclient-response").innerHTML = (data);
			$.ajax({
			url: 'includes/artworkResponse/projectsTableClientList.inc.php',
			success: function(data) {
			$("#projectoverview-addproject-projectClient").html(data);
			}
			}); // apple
		}
			
});

return false;
});
});
</script>

	<div>
		<div class="uk-card uk-card-default uk-card-body uk-margin-auto">

			<?php
			//Table Doesn't Exist So Allow User To Define Table

			require 'includes/artworkResponse/dbh.inc.php';

		
			$sql = "SELECT COUNT(*) AS NUMBEROFCOLUMNS FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = 'artworkresponse' AND table_name = '".strtolower($PROJECT_DATATABLE)."';";
			$result = mysqli_query($conn, $sql);
			$amountofCols = 0;
			if (mysqli_num_rows($result) > 0) {
				while($row = $result->fetch_assoc()) {
			  	$amountofCols = $row['NUMBEROFCOLUMNS'];	
			  	}
			}
			if($amountofCols > 2){
				
			
			?>

			<button class="uk-button uk-button-secondary" href="#" type="button" onclick="document.getElementById('tableEditor').style.display = 'block'; document.getElementById('tableResponses').style.display = 'none';" >Edit DataTable</button>
			<div id="tableEditor" style="display:none;">
				<form id="dataTable" method="post" action="includes/artworkResponse/ProjectDataTable.inc.php">
					<input id="editSQLStatement" name="sqlstatement" class='uk-input disable' placeholder="SQL Statement"></input>
				</form>
				<h3>Table Editor</h3>

				<div>
					
					<?php

					
					$sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'artworkresponse' AND TABLE_NAME = '".strtolower($PROJECT_DATATABLE)."';";
					$result = mysqli_query($conn, $sql);
					$count = 0;
					$dataType = '';
					if (mysqli_num_rows($result) > 0) {
						while($row = $result->fetch_assoc()) {
							echo "

							<div style='padding:5px 20px;'  id='selection".$count."'>
								<input style='width:10%;' class='uk-input disable' value='".$count."'></input>
								";

						if($count < 2){
							echo "<input id='selectionname".$count."' name='selectionname".$count."'  style='width:40%;' class='uk-input disable' placeholder='Name...'  data-sqlname='".$row['COLUMN_NAME']."' value='".$row['COLUMN_NAME']."'></input>";
						}else{
							(($row['DATA_TYPE'] == 'int')?$dataType = 'INT(255)':null);
							(($row['DATA_TYPE'] == 'bit')?$dataType = 'BIT(1)':null);
							(($row['DATA_TYPE'] == 'tinytext')?$dataType = 'TINYTEXT':null);
							(($row['DATA_TYPE'] == 'varchar')?$dataType = 'VARCHAR(65535)':null);
							(($row['DATA_TYPE'] == 'double')?$dataType = 'DOUBLE(65,5)':null);
							(($row['DATA_TYPE'] == 'float')?$dataType = 'DOUBLE(65,5)':null);

							echo "
								<input id='selectionname".$count."' name='selectionname".$count."' data-sqlname='".$row['COLUMN_NAME']."' style='width:40%;' class='uk-input' placeholder='Name...' value='".$row['COLUMN_NAME']."'></input>
								<select id='selectionvalue".$count."' data-sqldata='".$dataType."' name='selectionvalue".$count."'    style='width:35%;' class='uk-select'>
									<option value='INT(255)' ".(($row['DATA_TYPE'] == 'int')?"selected":"").">Int</option>
									<option value='BIT(1)' ".(($row['DATA_TYPE'] == 'bit')?"selected":"").">Bool</option>
									<option value='TINYTEXT' ".(($row['DATA_TYPE'] == 'tinytext')?"selected":"").">TinyText (255 Chars)</option>
									<option value='VARCHAR(65535)' ".(($row['DATA_TYPE'] == 'varchar')?"selected":"").">String (VarChar(65535))</option>
									<option value='DOUBLE(65,5)' ".(($row['DATA_TYPE'] == 'double')?"selected":"").">Float</option>
									<option value='FLOAT' ".(($row['DATA_TYPE'] == 'float')?"selected":"").">Float</option>
									<option value='DATETIME' ".(($row['DATA_TYPE'] == 'datetime')?"selected":"").">DateTime</option>
								</select>
								<button id='selectiondel".$count."' value='".$count."' class='uk-button-danger' style='width:5%; height:40px;' type='button' href='#' onclick=' deleteColumn(".$count.")'>X</button>"
								;
						}
						echo"</div>


							";

							
					  	$count = $count + 1;
					  	}



					}
					
						
					
					

					?>
					<div id="newRows">
						
					</div>
					<button class="uk-button uk-button-default" href="#" type="button" onclick="addNewRow();" style="width:100%;" > + Add New Row</button>

				</div>
				<br/>
				<button class="uk-button uk-button" href="#" type="button" onclick="location.reload();" >Cancel Editing</button>
				<button class="uk-button uk-button-secondary" href="#" onclick="alterSQLCommand();" type="button" >Save Table Changes</button>
			</div>
			<?php 

					  	echo "

					  	<script>

					  	var firstcommand = true;
					  	var totalLines = (".$count." - 1);

					  	function deleteColumn(num){
					  		if(firstcommand){
								firstcommand = false;
							}else{
								document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + ', ';
							}
					  		document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + 'DROP COLUMN ' + document.getElementById('selectionname'+num).value + ' '; 
					  		 document.getElementById('selection'+num).style.display = 'none';
					  	}

					  	function alterSQLCommand(){
					  	";

					  	for ($x = 2; $x < $count; $x++) {
 							
								
								echo "if(
							(document.getElementById('selectionname".$x."').value != document.getElementById('selectionname".$x."').getAttribute('data-sqlname')) || (document.getElementById('selectionvalue".$x."').value != document.getElementById('selectionvalue".$x."').getAttribute('data-sqldata'))){
								if(firstcommand){
									firstcommand = false;
								}else{
									document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + ', ';
								}
									document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + 'CHANGE '+document.getElementById('selectionname".$x."').getAttribute('data-sqlname')+' '+document.getElementById('selectionname".$x."').value+' '+document.getElementById('selectionvalue".$x."').value+' ';
								};

								

								";
								
						}



					  	echo"

						for(x = 0; x < linesToAdd; x++){
							if(firstcommand){
									firstcommand = false;
								}else{
									document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + ', ';
								}
							document.getElementById('editSQLStatement').value = document.getElementById('editSQLStatement').value + 'ADD '+document.getElementById('selectionnamenew'+x).value+ ' ' +document.getElementById('selectionvaluenew'+x).value+  ' ';
						}
						alterTableSQLCommand();

					  }

					  function alterTableSQLCommand(){

					var sqlcommand = 'ALTER TABLE ".strtolower($PROJECT_DATATABLE)." ' ;
					
						sqlcommand = sqlcommand + document.getElementById('editSQLStatement').value;

					
					document.getElementById('editSQLStatement').value = sqlcommand;
					//document.getElementById('dataTable').submit();

				}

					  	var linesToAdd = 0;

					  	function addNewRow(){
							totalLines = totalLines + 1;

							document.getElementById('newRows').innerHTML = document.getElementById('newRows').innerHTML + '<div style=\"padding:5px 20px;\"  id=\"selectionnew'+linesToAdd+'\"><input style=\"width:10%;\" class=\"uk-input disable\" value=\"'+totalLines+'\"></input><input id=\"selectionnamenew'+linesToAdd+'\" name=\"selectionnamenew'+linesToAdd+'\" style=\"width:40%;\" class=\"uk-input\" placeholder=\"Name...\" value=\"\"></input><select id=\"selectionvaluenew'+linesToAdd+'\" name=\"selectionvaluenew'+linesToAdd+'\"    style=\"width:35%;\" class=\"uk-select\"><option value=\"INT(255)\">Int</option><option value=\"BIT(1)\">Bool</option><option value=\"TINYTEXT\">TinyText (255 Chars)</option><option value=\"VARCHAR(65535)\">String (VarChar(65535))</option>	<option value=\"DOUBLE(65,5)\">Float</option></select><!--<button id=\"selectiondel'+totalLines+'\" class=\"uk-button-danger\" style=\"width:5%; height:40px;\" type=\"button\" href=\"#\">X</button>--></div>';
							linesToAdd = linesToAdd + 1;

					  	}
					  	</script>

					  	";

			?>
			<div id="tableResponses">
			<h3>Responses</h3>
			<br/>
			<div>
				<h4 style="margin-bottom:5px;">Unique Project Key</h4>
				<input class="uk-input" value="<?php echo $PROJECT_UNQIUEKEY ?>" readonly ></input>
			</div>
			<br/>
			</div>
			<?php

				echo "Results";
				echo "<div id='responseDataTable'><table class='uk-table uk-table-divider uk-table-striped uk-table-hover uk-table-justify' id='responseDataTableTable'><tr class='responseTableTR'>";

				$sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'artworkresponse' AND TABLE_NAME = '".strtolower($PROJECT_DATATABLE)."';";
				$result = mysqli_query($conn, $sql);
				$count = 0;
				$dataType = '';
				$columnNames = array();
				if (mysqli_num_rows($result) > 0) {
					while($row = $result->fetch_assoc()) {

						echo "<th>".$row['COLUMN_NAME']."</th>";
						$columnNames[] = $row['COLUMN_NAME'];
					}
				}
				echo "</tr>";

				

				$sql = "SELECT * FROM (SELECT * FROM ".strtolower($PROJECT_DATATABLE)." ORDER BY entryID DESC LIMIT 200) sub ORDER BY entryID DESC";
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					echo "<p>SQL Error</p>";
				} else {


					$result = mysqli_query($conn, $sql);
					//echo "<p>".$result->num_rows." projects Found</p>";
					if ($result->num_rows > 0) {
					  // output data of each row
					$echotext = "";
					  while($row = $result->fetch_assoc()) {
					  	echo"<tr class='responseTableTR'>";
					  	foreach($columnNames as $value){
					  		echo"<td>".$row[$value]."</td>";
					  	}
					  	
					  	echo"</tr>";
					  }
					}
				}

				echo "</table></div>";
				
			}else{
				?>
			<h2>Project Data Table</h2>
			<br/>
			<div>
				<h4 style="margin-bottom:5px;">Unique Project Key</h4>
				<input class="uk-input" value="<?php echo $PROJECT_UNQIUEKEY ?>" readonly ></input>
			</div>
			<br/>
			<p>No cells for data table. Please add some data inputs for the project.</p>
			<script>
				var dataamount = 1;

				function delRow(rowNum){

					//Remove Row and update all ids and name so that you can generate SQL statement easily.
					document.getElementById("selection"+rowNum).outerHTML = "";
					for(i = (parseInt(rowNum)+1); i < dataamount; i++){
						console.log("i: "+i);
						document.getElementById('selectionnum'+(i)).value = (document.getElementById('selectionnum'+(i)).value) -1;
						document.getElementById('selectiondel'+(i)).value = (document.getElementById('selectionnum'+(i)).value);
						document.getElementById('selectionnum'+(i)).id = 'selectionnum'+(i-1);
						document.getElementById('selection'+(i)).id = 'selection'+(i-1);
						document.getElementById('selectiondel'+(i)).id = 'selectiondel'+(i-1);
						document.getElementById('selectionname'+(i)).id = 'selectionname'+(i-1);
						document.getElementById('selectionvalue'+(i)).id = 'selectionvalue'+(i-1);
						document.getElementById('selectionvalue'+(i-1)).setAttribute("name",'selectionvalue'+(i-1));
						document.getElementById('selectionname'+(i-1)).setAttribute("name",'selectionname'+(i-1));
						document.getElementById('selectionnum'+(i-1)).setAttribute("name",'selectionnum'+(i-1));
						
					}
					dataamount = dataamount -1;
					
				}

				function createSQLcommand(){

					var sqlcommand = "ALTER TABLE <?php echo strtolower($PROJECT_DATATABLE) ?>" ;
					for(i = 1; i < dataamount; i++){

						if(i > 1){
							sqlcommand = sqlcommand + ",";
						}
						sqlcommand = sqlcommand + " ADD COLUMN "+document.getElementById('selectionname'+(i)).value+ " "+document.getElementById('selectionvalue'+(i)).value;

					}
					document.getElementById('sqlstatement').value = sqlcommand;
					document.getElementById('datatable').submit();

				}


			</script>
			
			<form id="datatable" method="post" action="includes/artworkResponse/ProjectDataTable.inc.php">
				<input class='uk-input disable' value="<?php echo $PROJECT_DATATABLE ?>"></input>
				<input class='uk-input disable' id="sqlstatement" name="sqlstatement" value=""></input>
				<div id="updateButton">
			<button class="uk-button uk-button-secondary" href="#" type="button" onclick="document.getElementById('dataTableList').innerHTML = document.getElementById('dataTableList').innerHTML + '<div id=\'selection'+dataamount+'\' style=\'padding:5px 20px;\'><input style=\'width:20%;\' class=\'uk-input disable\' id=\'selectionnum'+dataamount+'\' name=\'selectionnum'+dataamount+'\' value=\' '+dataamount+' \'></input><input id=\'selectionname'+dataamount+'\' name=\'selectionname'+dataamount+'\'  style=\'width:40%;\' class=\'uk-input\' placeholder=\'Name...\'></input><select id=\'selectionvalue'+dataamount+'\' name=\'selectionvalue'+dataamount+'\'  style=\'width:35%;\' class=\'uk-select\'><option value=\'INT(255)\'>Int</option><option value=\'BIT(1)\'>Bool</option><option value=\'TINYTEXT\'>TinyText (255 Chars)</option><option value=\'VARCHAR(65535)\'>String (VarChar(65535))</option><option value=\'DOUBLE(65,5)\'>Float</option></select><button id=\'selectiondel'+dataamount+'\' value=\''+dataamount+'\' class=\'uk-button-danger\' style=\'width:5%; height:40px;\' type=\'button\' href=\'#\' onclick=\'delRow(this.value); \' >X</button></div>'; 

			if(dataamount == 1){document.getElementById('updateButton').innerHTML = document.getElementById('updateButton').innerHTML+ '<button href=\'#\' type=\'button\' onclick=\'createSQLcommand();\' class=\'uk-button uk-button-primary\' name=\'CreateDataTable\'>Create Data Table</button> '; document.getElementById('dataTableList').inner }; dataamount = dataamount+1; ">Add Data</button></div>
			<div style='padding:5px 20px;'>
					<input style='width:20%;' class='uk-input disable' disabled value='0'></input>
					<input  style='width:40%;' class='uk-input disable' disabled placeholder='entryID'></input>
					<select style='width:35%;' class='uk-select' disabled>
						<option value='int' selected="">Int</option>
					</select>
			</div>
			<div style='padding:5px 20px;'>
					<input style='width:20%;' class='uk-input disable' disabled value='0'></input>
					<input  style='width:40%;' class='uk-input disable' disabled placeholder='entryDateTime'></input>
					<select style='width:35%;' class='uk-select' disabled>
						<option value='DateTime' selected="">DateTime</option>
					</select>
			</div>
			<div id="dataTableList"></div>
				
			</form>
			<?php 
			
			}
			?>
		</div>
	</div>


			
		
		<div id="projectview-changeProject" uk-modal>
				<div class="uk-modal-dialog" id="projectoverview-addproject-mainModal">
					<button class="uk-modal-close-default" type="button" uk-close></button>
					
					<form class="uk-form-stacked" action="includes/artworkResponse/projectstable.inc.php" method="post" id="projectoverview-addproject-form-projectCreation">
						<div class="uk-modal-header">
							<h2 class="uk-modal-title">Project Settings</h2>
						</div>
						<div class="uk-modal-body"  uk-overflow-auto>
							<input type="text" id="projectoverview-addproject-projectID" class="disable uk-input" name="projectID" value="<?php echo $projectID ?>">
							<label class="uk-form-label" for="projectName">Project Name:</label>
							<input type="text" id="projectoverview-addproject-projectName" name="projectName" class="uk-input" value="<?php echo $PROJECT_NAME?>" placeholder="Project Name...">
							<label class="uk-form-label" for="projectCode">Project Code:</label>
							<input type="text" id="projectoverview-addproject-projectCode" name="projectCode" class="disable uk-input" class="uk-input" value="<?php echo $PROJECT_CODE?>" placeholder="Project Code...">
							<label class="uk-form-label" for="projectDesc">Project Description:</label>
							<textarea class="uk-textarea" rows="3" name="projectDesc" placeholder="Textarea"><?php echo $PROJECT_DESC ?></textarea>
							<label class="uk-form-label" for="projectGitLink">Github Link:</label>
							<input type="text" id="projectoverview-addproject-projectGitLink" name="projectGitLink" class="uk-input" value="<?php echo $PROJECT_GITHUB ?>" placeholder="Github Link...">
							<label class="uk-form-label" for="projectLocation">Project Location:</label>
							<div style="display: flex;">
								
								<input type="text" id="projectoverview-addproject-projectLocation" name="projectLocation" class="uk-input" placeholder="NEW Project Address...">
								<button class="uk-button uk-button-secondary" type="button" href="#" onclick="
								var uri = document.getElementById('projectoverview-addproject-projectLocation').value;
								var res = encodeURI(uri);
								var response = '';
								var items = [];
								var location = [];
								$.getJSON('https://api.mapbox.com/geocoding/v5/mapbox.places/'+res+'.json?access_token='+mapAccessToken, function(data) {
								
								
								$.each( data.features, function( key, val ) {
								response = response + '<button style=\'width:100%\' class=\'uk-button uk-button-default\' value=\''+val.center+'\' type=\'button\' href=\'#\' onclick=\' document.getElementById(&quot;projectoverview-addproject-projectLocationLongitude&quot;).value = '+val.center[0]+'; document.getElementById(&quot;projectoverview-addproject-projectLocationLatitude&quot;).value = '+val.center[1]+'; generateMap();\' >'+val.place_name+'</button>';
								items.push(val.place_name);
								location.push(val.center);
								
								});
								console.log(data);
								if(items.length == 0){
								response = 'Location Not Found';
								}
								$('#projectoverview-addproject-projectLocationEntries').html(response);
								});
								
								
								"><i class="fa fa-search" aria-hidden='true'></i></button>
							</div>
							<div id="projectoverview-addproject-projectLocationEntries">
							</div>
							
							<img id="projectoverview-addproject-projectLocationMap" src="" />
							
							<div style="display: flex;">
								
								<input type="text" id="projectoverview-addproject-projectLocationLongitude" name="projectLocationLongitude" class="uk-input" readonly  placeholder="Longitude..." value="<?php echo $PROJECT_LONG ?>" > 
								<input type="text" id="projectoverview-addproject-projectLocationLatitude" name="projectLocationLatitude" class="uk-input" readonly  placeholder="Latitude..." value="<?php echo $PROJECT_LAT ?>">
							</div>
							<label class="uk-form-label" for="projectStatus">Project Status:</label>
							<select name="projectStatus" class="uk-select" id="projectoverview-addproject-projectStatus" oninput="if(this.value == 'Other'){document.getElementById('projectoverview-addproject-other').style.display = 'block'; document.getElementById('projectoverview-addproject-projectStatustext').value = '';}else{document.getElementById('projectoverview-addproject-other').style.display = 'none'; document.getElementById('projectoverview-addproject-projectStatustext').value = this.value;}">
								<option value="Production">Production</option>
								<option value="Released">Released</option>
								<option value="Offline">Offline</option>
								<option value="Not Supported">Not Supported</option>
								<option value="Other">Other</option>
							</select>
							<div id="projectoverview-addproject-other" style="display:none;">
								<input type="text" id="projectoverview-addproject-projectStatustext" name="projectStatustext" value="<?php echo $PROJECT_STATUS ?>" class="uk-input" placeholder="Enter Custom Status...">
							</div>
							
							
							<label class="uk-form-label" for="projectClient">Client:</label>
							
							
							<div style="display: flex;">
								
								<select name="projectClient"  class="uk-select" id="projectoverview-addproject-projectClient">
									<?php
									require('includes/artworkResponse/projectsTableClientList.inc.php');
									
									?>
									
								</select>
								<button class="uk-button uk-button-default" type="button" href="#" onclick="document.getElementById('projectoverview-addproject-form-projectCreation').style.display='none';document.getElementById('projectoverview-addproject-form-addClient').style.display='block';" style="width:200px; float:left;">Add Client</button>
							</div>
							<label class="uk-form-label" for="projectMainenancePeriod">Maintenance Period:</label>
							<select name="projectMainenancePeriod" class="uk-select" id="projectoverview-addproject-projectMainenancePeriod">
								<option value="0">No Period</option>
								<option value="3">Every 3 Months</option>
								<option value="6">Every 6 Months</option>
								<option value="12">Every 12 Months</option>
								<option value="24">Every 24 Months</option>
							</select>

							<label class="uk-form-label" for="projectinstallDate">Installation Date:</label>
							<input type="date" class="uk-input" name="projectinstallDate" value="<?php echo $PROJECT_INSTALLDATE ?>">
							<!-- <label class="uk-form-label" for="projectTeamMembers">Team:</label>
							<select class="uk-select" id="projectoverview-addproject-projectTeamMembers" name="projectTeamMembers" multiple>
								<?php
										/*require('includes/artworkResponse/projectsTableTeamList.inc.php');*/
										
								?>
								
							</select> -->
							<label class="uk-form-label" for="projectBGLink">Background Image Link:</label>
							<input type="text" id="projectoverview-addproject-projectBGLink" name="projectBGLink" class="uk-input" value="<?php echo $PROJECT_BG ?>" placeholder="Backgroud Image URL...">

							

						</div>
						<div class="uk-modal-footer uk-text-right">
							<button class="uk-button uk-button-default uk-modal-close" type="button" onclick="document.getElementById('projectoverview-addproject-projectName').value = '';document.getElementById('projectoverview-addproject-projectCode').value = '';document.getElementById('projectoverview-addproject-projectStatustext').value = 'Production';">Cancel</button>
							<button class="uk-button uk-button-danger" name="projectoverview-updateproject-delete" type="submit">Delete Project</button>
							<button class="uk-button uk-button-primary" name="projectoverview-updateproject-submit" type="submit">Update Project</button>
						</div>
					</form>
					<form class="uk-form-stacked" method="post" id="projectoverview-addproject-form-addClient" style="display:none">
						<div class="uk-modal-header">
							<h2 class="uk-modal-title"><a onclick="document.getElementById('projectoverview-addproject-form-projectCreation').style.display='block';document.getElementById('projectoverview-addproject-form-addClient').style.display='none';">
							<i class="fa fa-chevron-left" aria-hidden='true'></i></a> &nbsp; &nbsp; Add Client</h2>
						</div>
						<div class="uk-modal-body"  uk-overflow-auto>
							<div id="projectoverview-addclient-response"></div>
							<label class="uk-form-label" for="clientName">Client Code:</label>
							<input type="text" id="projectoverview-addclient-clientCode" name="clientCode" class="uk-input" placeholder="Client Code...">
							<label class="uk-form-label" for="clientName">Client Name:</label>
							<input type="text" id="projectoverview-addclient-clientName" name="clientName" class="uk-input" placeholder="Client Full Name...">
							<button class="uk-button uk-button-primary" name="projectoverview-addclient-submit" id="projectoverview-addclient-submit" type="submit">Add Client</button>
						</div>

					</form>
					
				</div>
			</div>

	</main>
		
	<script>
		function setClientSelected(name){
			Array.from(document.querySelector("#projectoverview-addproject-projectClient").options).forEach(function(option_element) {
			    if(option_element.value == name){
			    	option_element.setAttribute('selected', true);

			    }
			    
			});

		}

		function setMaintancePeriod(period){ 
			Array.from(document.querySelector("#projectoverview-addproject-projectMainenancePeriod").options).forEach(function(option_element) {
			    if(option_element.value == period){
			    	option_element.setAttribute('selected', true);

			    }
			    
			});

		}

		function setProjectStatus(status){ 
			Array.from(document.querySelector("#projectoverview-addproject-projectStatus").options).forEach(function(option_element) {
			    if(option_element.value == status){
			    	option_element.setAttribute('selected', true);

			    }
			    
			});

		}

		
	</script>

<?php 
	require "footer.php";
 }else{ header("Location: index.php");} 
 ?>

