<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>

<?php 
	require "sidebarMenu.php";
?>

		

	<main id="dashboard-main">
		<div id="">
			<h2>dashboard</h2>

		</div>
	</main>
		
	

<?php 
	require "footer.php";
?>

<?php }else{ header("Location: index.php");} ?>

