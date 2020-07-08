<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>

<?php 
	require "sidebarMenu.php";
?>

		

	<main id="dashboard-main">
		<div id="centeredDiv" class="uk-margin-auto">
			<h1 class="uk-heading-line"><span>Project Overview</span></h1>
			<section>
				<button class="uk-button uk-button-secondary" href="#projectOverview-AddProject" uk-toggle>New Project</button>
				<div id="projectOverview-AddProject" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Add Project</h2>
        </div>
		<form class="uk-form-stacked" action="includes/artworkResponse/projectstable.inc.php" method="post">
        <div class="uk-modal-body" uk-overflow-auto>

            
            	<label class="uk-form-label" for="projectName">Project Name:</label>
            	<input type="text" id="projectoverview-addproject-projectName" name="projectName" class="uk-input" placeholder="Project Name...">
            	<label for="projectCode">Project Code:</label>
            	<input type="text" id="projectoverview-addproject-projectCode" name="projectCode" class="uk-input" placeholder="Project Code...">
				<label for="projectStatus">Project Status:</label>
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
           

        </div>

        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button" onclick="document.getElementById('projectoverview-addproject-projectName').value = '';document.getElementById('projectoverview-addproject-projectCode').value = '';document.getElementById('projectoverview-addproject-projectStatustext').value = 'Production';">Cancel</button>
            <button class="uk-button uk-button-primary" name="projectoverview-addproject-submit" type="submit">Add Project</button>
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
		
	

<?php 
	require "footer.php";
?>

<?php }else{ header("Location: index.php");} ?>

