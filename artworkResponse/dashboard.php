<?php 
	require "header.php";
?>

<?php if(isset($_SESSION['userID'])){ ?>

<?php 
	require "sidebarMenu.php";
?>

		

	<main id="dashboard-main">

<div id='map' style='height: 300px;'></div>


		<div id="">
			<h2>dashboard</h2>

			<div>
				<form action="includes/artworkResponse/dashboard.inc.php" method="post">
					<button name="refreshPins" class="uk-button uk-button-default">Refresh Pins</button>
				</form>
			</div>

		</div>
	</main>
		
	
<script>
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
			}else{
				echo "el.className = 'marker marker-notactive';";
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

