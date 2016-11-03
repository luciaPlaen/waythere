<?php
    require_once('../system/config.php');              // config-Datei einbinden


    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert
    $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

        
    $nextSpot = 1;      // beschreibt die aktuell nächste Station einer Route - das nächste Ziel der Routenführung

    $destination = mysqli_fetch_array(get_spot_coordinates($route_id, $nextSpot), MYSQLI_ASSOC);
    $destinationLatitude = $destination['latitude'];
    $destinationLongitude = $destination['longitude'];

    $api_settings = get_api_settings();

    ?>



<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="../style/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - routenführung</title>
    
</head>

<body>
    
    <h1> Routenführung </h1>
    
    <p id = "posLat">posLat</p>
    <p id = "posLong">posLong</p>
    <p id = "destLat">destLat</p>
    <p id = "destLong">destLong</p>
    <p id = "request">http-request ...</p>
    <p id = "xml">xml ...</p>
    
    
    <script>
        
        
        function instructions() {
            var step_counter = 0;
            document.getElementById("xml").innerHTML = xmlDoc.getElementsByTagName("html_instructions")[step_counter].childNodes[step_counter].nodeValue;
            
            
 /*
var id, ziel, options;
//Verfolgen beginnen
id = navigator.geolocation.watchCurrentPosition(verfolgePosition);
 
function verfolgePosition(pos) {
  var aktuell = pos.coords;
 
  if (ziel.latitude === aktuell.latitude && ziel.longitude === aktuell.longitude) {
    console.log('Sie haben Ihr Ziel erreicht');
    //Verfolgen beenden
    navigator.geolocation.clearWatch(id);
  }
}
 
ziel = {
  latitude : 0,
  longitude: 0
};
 
options = {
  enableHighAccuracy: false,
  timeout: 5000,
  maximumAge: 0
};
*/         
            
        }
        
        
        function spot_request() {
            
        /* 
        if(navigator.geolocation) {
            alert('Geolocation verfügbar');
        } else {
            alert('Geolocation nicht verfügbar');
        } */
            
        navigator.geolocation.watchPosition(function(position){ 
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("destLat").innerHTML = "<?php echo $destinationLatitude; ?>";
            document.getElementById("destLong").innerHTML = "<?php echo $destinationLongitude; ?>";
            document.getElementById("posLat").innerHTML = position.Latitude;
            document.getElementById("posLong").innerHTML = position.Longitude;
            
            var apiRequest = "https://maps.googleapis.com/maps/api/directions/xml?origin="+positionLatitude+","+positionLongitude+"&destination=<?php echo $destinationLatitude; ?>,<?php echo $destinationLongitude; ?>&mode=<?php echo $api_settings[1]; ?>&language=<?php echo $api_settings[2]; ?>&key=<?php echo $api_settings[0]; ?>";
            document.getElementById("request").innerHTML = apiRequest;
            
            //var xml = file_get_contents(apiRequest);
            //document.getElementById("xml").innerHTML = xml;
            

            var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var xml =this.responseText;
                        console.log(xml);
                        // document.getElementById("xml").innerHTML = "empfangen!";
                        
                        parser = new DOMParser();
                        xmlDoc = parser.parseFromString(xml,"text/xml");

                        instructions ();
                        
                    }
                };
                xhttp.open("GET", "xml_request.php?url="+apiRequest, true);
                xhttp.send();
            
            
        }, function() {
            document.getElementById("posLat").innerHTML = "deine Position konnte leider nicht ermittelt werden.";
            document.getElementById("posLong").innerHTML = " ";
        });
        }
        
        spot_request();
        
    </script>
    
    
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>