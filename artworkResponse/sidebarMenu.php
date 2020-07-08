<?php
        require 'includes/artworkResponse/dbh.inc.php';

        $sql = "SELECT * FROM users WHERE appovedUser=0";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
           $usersAwaitingapproval = "?";
        } else {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $usersAwaitingapproval = mysqli_stmt_num_rows($stmt);
            if($usersAwaitingapproval >0){
                $userNotification = "<span style='float:right;margin-right:10px;' class='uk-badge'>".$usersAwaitingapproval."</span>";
            }else{
                $userNotification = "";   
            }
        }
?>

<div id="dashboard-sidebar">
    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
    	<div>
	    	<?php echo "<p style='color:#ffffff'>Welcome, ".$_SESSION['fName']." </p>"; ?>

			<form id="header-logout" action="includes/artworkResponse/logout.inc.php" method="post">
				<button class="uk-button" type="submit" name="logout-submit">Logout</button>
			</form>
		</div>
		<br/>
        <li id="sidebar-dashboard-button"><a href="dashboard.php">DashBoard</a></li>
        <li class="uk-parent">
            <a href="#">Projects</a>
            <ul class="uk-nav-sub">
                <li><a href="projectOverview.php">Project Overview</a></li>
                <li>
                    <a href="#">Starred Projects</a>
                    <ul>
                        <li><a href="#">Sub item</a></li>
                        <li><a href="#">Sub item</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <!--<li id="sidebar-users-button"><a href="users.php">Users </a></li>-->
         <li class="uk-parent" id="sidebar-users">
            <a href="#">Users<?php echo $userNotification ;?></a>
            <ul class="uk-nav-sub" id="sidebar-users-submenu">
                <li id="sidebar-users-button"><a href="users.php">All Users<?php echo $userNotification ;?></a></li>
                <li id="sidebar-users-settings"><a href="#">My Settings</a></li>
            </ul>
        </li> 
    </ul>
</div>

<script>
    var url = document.URL;
    var pagename = url.substring(url.lastIndexOf("/")+1, url.length);
    switch(pagename){
        case "dashboard.php":
            document.getElementById("sidebar-dashboard-button").classList.add("uk-active");
        break;
        case "users.php":
            document.getElementById("sidebar-users-button").classList.add("uk-active");
            document.getElementById("sidebar-users").classList.add("uk-open");
            document.getElementById("sidebar-users-submenu").hidden = false;
            /*document.getElementById("sidebar-users-button").classList.add("uk-active");*/
            
            
            
        break;
    }

</script>