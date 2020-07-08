<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>

<?php 
	require "sidebarMenu.php";
?>

		

	<main id="dashboard-main">
		<div id="centeredDiv" class="uk-margin-auto">
			<h1 class="uk-heading-line"><span>PROJECT VIEW</span></h1>
			<section>
				<table class="uk-table uk-table-striped">
				    <thead>
				        <tr>
				            <th>User ID</th>
				            <th>First Name</th>
				            <th>Last Name</th>
				            <th>Email</th>
				            <th>Approved User</th>
				            <th>Actions</th>
				        </tr>
				    </thead>
				    <tbody>
				<?php require "includes/artworkResponse/usertable.inc.php" ?>
				</tbody>
				</table>
			</section>
		</div>
	</main>
		
	

<?php 
	require "footer.php";
?>

<?php }else{ header("Location: index.php");} ?>

