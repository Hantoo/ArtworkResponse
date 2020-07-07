<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>


		
	<div id="dashboard-sidebar">
		
    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
    	<?php echo "<p>Welcome, ".$_SESSION['fName']." </p>"; ?>

		<form id="header-logout" action="includes/artworkResponse/logout.inc.php" method="post">
			<button class="uk-button" type="submit" name="logout-submit">Logout</button>
		</form>

        <li class="uk-active"><a href="#">Active</a></li>
        <li class="uk-parent">
            <a href="#">Projects</a>
            <ul class="uk-nav-sub">
                <li><a href="#">Sub item</a></li>
                <li>
                    <a href="#">Sub item</a>
                    <ul>
                        <li><a href="#">Sub item</a></li>
                        <li><a href="#">Sub item</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="uk-parent">
            <a href="#">Users</a>
            <ul class="uk-nav-sub">
                <li><a href="#">Awaiting Approval <span style="float:right;margin-right:10px;" class="uk-badge">1</span></a></li>
                <li><a href="#">All Users</a></li>
            </ul>
        </li>
    </ul>

	</div>
	<main id="dashboard-main">
		<div id="">
			<h2>dashboard</h2>

		</div>
	</main>
		
	

<?php 
	require "footer.php";
?>

<?php }else{ header("Location: index.php");} ?>

