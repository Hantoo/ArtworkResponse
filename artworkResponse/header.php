<?php 
session_start();
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/styles.css">

		<!-- UIkit CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.5.4/dist/css/uikit.min.css" />

		<!-- UIkit JS -->
		<script src="https://cdn.jsdelivr.net/npm/uikit@3.5.4/dist/js/uikit.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/uikit@3.5.4/dist/js/uikit-icons.min.js"></script>

		<!-- Font Awesome -->
		<script src="https://use.fontawesome.com/ca64a5da55.js"></script>

		<!-- MapBox -->
		<script src='https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.js'></script>
		<link href='https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.css' rel='stylesheet' />
		<script>
			var mapAccessToken = "[INSERT MAPBOX TOKEN HERE]";
		</script>

		<!-- JQUERY -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

		<title>Response</title>
	</head>
		<!-- Logout button -->
		<?php if(isset($_SESSION['userID'])){ ?>
		<body>
			<script>
				
				$(document).keypress(
				  function(event){
				    if (event.which == '13') {
				      event.preventDefault();
				    }
				});

			</script>

			<div style="display:flex;  ">
		
		<?php }else{ ?>
			<body id="homepage-body"><div>
		<?php } ?>
