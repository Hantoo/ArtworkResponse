<?php
	require "header.php";
?>
<?php if(isset($_SESSION['userID'])){ ?>
<?php
	require "sidebarMenu.php";
?>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
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


$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
</script>
<main id="dashboard-main">
	<div id="centeredDiv" class="uk-margin-auto">
		<h1 class="uk-heading-line"><span>Project Overview</span></h1>
		<?php
				if(isset($_GET['error'])){
					if($_GET['sqlerror'] == "emptyfeilds"){
						$msg = "SQL Command Could Not Be Run. Please Check Syntax: "+ $_GET['command'];
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-danger'>".$msg."</p>";
				}
				/*if(isset($_GET['signup'])){
					if($_GET['signup'] == "sucess"){
						$msg = "User Sucessfully Created. Please Await Approval.";
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-success'>".$msg."</p>";
				}*/
			?>
		<section>
			<button class="uk-button uk-button-secondary" href="#projectOverview-AddProject" uk-toggle>New Project</button>
			<div id="projectOverview-AddProject" uk-modal>
				<div class="uk-modal-dialog" id="projectoverview-addproject-mainModal">
					<button class="uk-modal-close-default" type="button" uk-close></button>
					
					<form class="uk-form-stacked" action="includes/artworkResponse/projectstable.inc.php" method="post" id="projectoverview-addproject-form-projectCreation">
						<div class="uk-modal-header">
							<h2 class="uk-modal-title">Add Project</h2>
						</div>
						<div class="uk-modal-body"  uk-overflow-auto>
							
							<label class="uk-form-label" for="projectName">Project Name:</label>
							<input type="text" id="projectoverview-addproject-projectName" name="projectName" class="uk-input" placeholder="Project Name...">
							<label class="uk-form-label" for="projectCode">Project Code:</label>
							<input type="text" id="projectoverview-addproject-projectCode" name="projectCode" class="uk-input" placeholder="Project Code...">
							<label class="uk-form-label" for="projectDesc">Project Description:</label>
							<textarea class="uk-textarea" rows="3" name="projectDesc" placeholder="Textarea"></textarea>
							<label class="uk-form-label" for="projectGitLink">Github Link:</label>
							<input type="text" id="projectoverview-addproject-projectGitLink" name="projectGitLink" class="uk-input" placeholder="Github Link...">
							<label class="uk-form-label" for="projectLocation">Project Location:</label>
							<div style="display: flex;">
								
								<input type="text" id="projectoverview-addproject-projectLocation" name="projectLocation" class="uk-input" placeholder="Project Address...">
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
								
								<input type="text" id="projectoverview-addproject-projectLocationLongitude" name="projectLocationLongitude" class="uk-input" readonly  placeholder="Longitude...">
								<input type="text" id="projectoverview-addproject-projectLocationLatitude" name="projectLocationLatitude" class="uk-input" readonly  placeholder="Latitude...">
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
								<input type="text" id="projectoverview-addproject-projectStatustext" name="projectStatustext" value="Production" class="uk-input" placeholder="Enter Custom Status...">
							</div>
							
							
							<label class="uk-form-label" for="projectClient">Client:</label>
							
							<div style="display: flex;">
								
								<select name="projectClient" class="uk-select" id="projectoverview-addproject-projectClient">
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
							<!-- <label class="uk-form-label" for="projectTeamMembers">Team:</label>
							<select class="uk-select" id="projectoverview-addproject-projectTeamMembers" name="projectTeamMembers" multiple>
								<?php
										/*require('includes/artworkResponse/projectsTableTeamList.inc.php');*/
										
								?>
								
							</select> -->
							<label class="uk-form-label" for="projectBGLink">Background Image Link:</label>
							<input type="text" id="projectoverview-addproject-projectBGLink" name="projectBGLink" class="uk-input" placeholder="Backgroud Image URL...">
						</div>
						<div class="uk-modal-footer uk-text-right">
							<button class="uk-button uk-button-default uk-modal-close" type="button" onclick="document.getElementById('projectoverview-addproject-projectName').value = '';document.getElementById('projectoverview-addproject-projectCode').value = '';document.getElementById('projectoverview-addproject-projectStatustext').value = 'Production';">Cancel</button>
							<button class="uk-button uk-button-primary" name="projectoverview-addproject-submit" type="submit">Add Project</button>
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
			<table class="uk-table uk-table-striped">
				<thead>
					<tr>
						<th>Online</th>
						<th>UnquieID</th>
						<th>Project Code</th>
						<th>Name</th>
						<th>Project Status</th>
						<th>Creation Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php require "includes/artworkResponse/projectstable.inc.php" ?>
				</tbody>
			</table>
		</section>
	</div>
</main>
<script>
									function generateMap(){
									document.getElementById('projectoverview-addproject-projectLocationMap').src = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/geojson(%7B%22type%22%3A%22Point%22%2C%22coordinates%22%3A%5B"+document.getElementById('projectoverview-addproject-projectLocationLongitude').value+"%2C"+document.getElementById('projectoverview-addproject-projectLocationLatitude').value+"%5D%7D)/"+document.getElementById('projectoverview-addproject-projectLocationLongitude').value+","+document.getElementById('projectoverview-addproject-projectLocationLatitude').value+",14.25,0,60/600x600?access_token="+mapAccessToken;
								}
</script>
<?php
	require "footer.php";
?>
<?php }else{ header("Location: index.php");} ?>