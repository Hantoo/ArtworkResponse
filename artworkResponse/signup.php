<?php 
	require "header.php";
?>
	<script>
		function checkValuesAgainstEachOther(value1, value2){
			if(value1 == value2){
				document.getElementById("signup-password-error").innerHTML = "Passwords Match!";
				document.getElementById("signup-password-error").classList.remove("uk-alert-danger");
				document.getElementById("signup-password-error").classList.add("uk-alert-success");
				document.getElementById("signup-submit").disabled = false;
			}else{
				document.getElementById("signup-password-error").innerHTML = "Passwords Are Not The Same.";
				document.getElementById("signup-password-error").classList.remove("uk-alert-success");
				document.getElementById("signup-password-error").classList.add("uk-alert-danger");
				document.getElementById("signup-submit").disabled = true;
			}
		}
	</script>
	
		<div id="signup-maindiv">
			<h1>Create Account</h1>
			<p>Once you have created an account, your account must be approved by an administrator before you can login.</p>
			<?php
				if(isset($_GET['error'])){
					if($_GET['error'] == "emptyfeilds"){
						$msg = "Empty Fields";
					}else if($_GET['error'] == "wrongmail"){
						$msg = "Email Format Not valid";
					}else if($_GET['error'] == "sqlerror"){
						$msg = "SQL Error - Contact Admin";
					}else if($_GET['error'] == "userexists"){
						$msg = "Email Is Already Used By A User";
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-danger'>".$msg."</p>";
				}
				if(isset($_GET['signup'])){
					if($_GET['signup'] == "sucess"){
						$msg = "User Sucessfully Created. Awaiting Approval From Admin.";
					}
					echo "<p id='homepage-serverResponse' class='uk-alert-success'>".$msg."</p>";
				}
			?>
			
			<form action="includes/artworkResponse/signup.inc.php" method="post">
				<input class="uk-input" type="text" name="fname" placeholder="First Name" required>
				<input class="uk-input" type="text" name="lname" placeholder="Last Name" required>
				<input class="uk-input" type="text" name="mailuid" placeholder="Username / Email" required>
				<input class="uk-input" type="password" name="pwd" id="signup-pwd1" placeholder="Password" required>
				<input class="uk-input" type="password" name="pwd-verify" id="signup-pwd2" oninput="checkValuesAgainstEachOther(document.getElementById('signup-pwd1').value,document.getElementById('signup-pwd2').value)" placeholder="Repeat Password" required>
				<p class='uk-alert-danger' id="signup-password-error"></p>
				<button class="uk-button" type="submit" name="signup-submit" id="signup-submit">Create Account</button>
			</form>
		
		</div>


		
	
	

<?php 
	require "footer.php";
?>