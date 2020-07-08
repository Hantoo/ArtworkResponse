 <?php 
	require "header.php";
?>
		
	
		<div id="homepage-login">
		
		<!-- <?php
		/*if(isset($_SESSION['userID'])){
			echo '<p>User Logged In</p>';
		}else{
			echo '<p class="uk-alert-danger">User Logged Out</p>';
		}*/
		
		
		?> -->

			<h2>Login</h2>
			<?php
				if(isset($_GET['error'])){
					if($_GET['error'] == "emptyfeilds"){
						$msg = "Empty Fields";
					}else if($_GET['error'] == "wrongmail"){
						$msg = "Email Format Not valid";
					}else if($_GET['error'] == "sqlerror"){
						$msg = "SQL Error - Contact Admin";
					}else if($_GET['error'] == "wrongpassword"){
						$msg = "Password Is Incorrect";
					}else if($_GET['error'] == "usernotapproved"){
						$msg = "User Awaiting Approval";
					}if($_GET['error'] == "nouser"){
						$msg = "User Doesn't Exist";
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-danger'>".$msg."</p>";
				}
				if(isset($_GET['signup'])){
					if($_GET['signup'] == "sucess"){
						$msg = "User Sucessfully Created. Please Await Approval.";
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-success'>".$msg."</p>";
				}
			?>
			<form action="includes/artworkResponse/login.inc.php" method="post">
				<input class="uk-input" type="text" name="mailuid" placeholder="Username / Email">
				<input class="uk-input" type="password" name="pwd" placeholder="Password">
				<button class="uk-button" type="submit" name="login-submit">Login</button>
			</form>
			<a href="signup.php" >Create Account</a>
			
		</div>

		
	

<?php 
	require "footer.php";
?>