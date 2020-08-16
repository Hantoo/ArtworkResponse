<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>

<?php 
	require "sidebarMenu.php";
?>

		

	<main id="dashboard-main">

		<div id='map' style='height: 50vh;'></div>


		<div id="dashboard-undermap">
			<h1 class="uk-heading-divider" style="margin-left:15px;">Dashboard</h1>
			<br/>
			<div id="dashboard-cardSection">
			<div class="uk-card uk-card-default" style="width:fit-content;">
				<div class="uk-card-body">
					<h3 class="uk-card-title">Projects Online</h3>
					<h2 id="dashboard-projectsOnlineNum" class="dashboard-cardCounterNum"></h2>
					
				</div>
			</div>
			<div class="uk-card uk-card-default" style="width:fit-content;">
				<div class="uk-card-body">
					<h3 class="uk-card-title">Projects Offline</h3>
					<h1 id="dashboard-projectsOfflineNum" class="dashboard-cardCounterNum"></h1>
					
				</div>
			</div>
			<div class="uk-card uk-card-secondary" style="width:fit-content;">
				<div class="uk-card-body">
					<h3 class="uk-card-title">Settings</h3>
					<h4>Refresh Settings:</h4>
					<p id="dashboard-refreshcountdown">Automatic Refresh In 900 Seconds. </p>
					<form id="dashboard-refreshPinsForm" action="includes/artworkResponse/dashboard.inc.php" method="post">
						<button name="refreshPins" class="uk-button uk-button-default">Refresh Now</button>
					</form>
				</div>
			</div>
		</div>
			
		</div>
	</main>
		
<script>
var timerDefault = 900000; // 15mins in ms
const startedTime = Date.now() + timerDefault;
var countdowntext = document.getElementById("dashboard-refreshcountdown");
var x = setInterval(function() {

  // Get today's date and time
  var now = Date.now();
  var millis = (startedTime-now);
  var countdown =  Math.floor(millis / 1000);

  countdowntext.innerHTML = "Automatic Refresh In " + countdown+ " Seconds.";
  if(countdown <= 1){
  	document.getElementById("dashboard-refreshPinsForm").submit();
  }
  }, 1000);


</script>
<script>
var projectsOnline = 0;
var projectsOffline = 0;
mapboxgl.accessToken = mapAccessToken;
var map = new mapboxgl.Map({
container: 'map',
style: 'mapbox://styles/mapbox/dark-v10',
zoom: 1,
center: [0, 40.46]
});
map.addControl(new mapboxgl.FullscreenControl());
<?php
require 'includes/artworkResponse/dbh.inc.php';

$sql = 'SELECT projectdetails.projectID, projectdetails.projectLongitude, projectdetails.projectLatitude, projects.name, projects.online FROM projectdetails INNER JOIN projects ON projectdetails.projectID = projects.unqiueID';
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		//Add Icon
	   echo "var el = document.createElement('div');";
	   		if($row["online"] == true){
				echo "el.className = 'marker';";
				echo "projectsOnline = projectsOnline + 1;";
			}else{
				echo "el.className = 'marker marker-notactive';";
				echo "projectsOffline = projectsOffline + 1;";
			}
			echo "new mapboxgl.Marker(el)
			.setLngLat([".$row["projectLongitude"].", ".$row["projectLatitude"]."])
			.setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
			.setHTML('<h3>".$row["name"]."</h3> <a href=\"projectview.php?project=".$row["projectID"]."\"><button class=\"uk-button uk-button-default\" href=\"#\">View</button></a>'))
			.addTo(map);
			  ";


	}
}

?>

document.getElementById('dashboard-projectsOnlineNum').innerHTML = projectsOnline;
document.getElementById('dashboard-projectsOfflineNum').innerHTML = projectsOffline;
//TODO make map go through every project entry, make a marker for its location and change the colour of the marker depending on if its online of offline 
/*
new Marker([0, 51.4934], {
    icon: divIcon({
        // specify a class name that we can refer to in styles, as we
        // do above.
        className: 'fa-icon',
        // html here defines what goes in the div created for each marker
        html: '<i class="fa fa-circle" aria-hidden="true"></i>',
        // and the marker width and height
        iconSize: [40, 40]
    })
}).addTo(map);*/

</script>
<?php 
	require "footer.php";
?>

<?php }else{ header("Location: index.php");} ?>

