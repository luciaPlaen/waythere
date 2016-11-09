<?php
    require_once('../system/config.php');              // config-Datei einbinden


    if(isset($_GET['id'])) {
        // die per GET (in der URL) übergebene id wird als $route_id gespeichert
        $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

    $lastSpot = count_spots($route_id);
    $spotCount = 1;
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
    <title>waythere stories - guide</title>
    
</head>

<body>
    
    <h1> Routenführung </h1>
    <br>
    <p id = "spotDest"></p>
    <br>
    <p id = "posLat"></p>
    <p id = "posLong"></p>
    <br>
    <p id = "instruction"></p>
    <br>
    <a href ="route.php">abbrechen</a>
    
    
    <script>
        
        
        // Abfragen der Koordinaten jedes zur Route gehörigen Spots >> Speichern der Werte in 2 Arrays (Latitude & Longitude) >> der Index entspricht jeweils der Spot-Nummerierung (dem Array wird der einfacheren Handhabung halber ein Anfangswert (0) mitgegeben, um diesen Umstand zu erreichen)
        var spotsLatitude = [0];
        var spotsLongitude = [0];
        <?php
            $all_spot_coordinates = get_all_spot_coordinates($route_id);
            while ($line = mysqli_fetch_array($all_spot_coordinates, MYSQLI_ASSOC)) { ?>
                spotsLatitude.push(<?php echo $line['latitude']; ?>);
                spotsLongitude.push(<?php echo $line['longitude']; ?>);
        <?php
            } ?>
        
        
        function watchSuccess(position, nextSpot) {
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("posLat").innerHTML = "PositionLatitude: "+positionLatitude;
            document.getElementById("posLong").innerHTML = "PositionLongitude: "+positionLongitude;
            console.log("Position wurde aktualisiert zu: Latitude: "+positionLatitude+" & Longitude: "+positionLongitude);

            request = "https://maps.googleapis.com/maps/api/directions/xml?origin="+positionLatitude+","+positionLongitude+"&destination="+spotsLatitude[nextSpot]+","+spotsLongitude[nextSpot]+"&mode=<?php echo $api_settings[1]; ?>&language=<?php echo $api_settings[2]; ?>&key=<?php echo $api_settings[0]; ?>";
            // console.log(request);
            
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var xml =this.responseText;
                    console.log("XML-Datei empfangen!");
                        
                    parser = new DOMParser();
                    xmlDoc = parser.parseFromString(xml,"text/xml");    
                }
            };
            xhttp.open("GET", "xml_request.php?url="+request, false);
            xhttp.send();
            
            // Vergleichen >> PUFFERZONE BERÜCKSICHTIGEN !!! (Distanz von Position zu Ziel berechnen (haversine) und wenn Distanz kleiner als ...)
            
            if (positionLatitude != spotsLatitude[nextSpot]) {
                console.log ("Position stimmt nicht mit Ziel überein!");
                document.getElementById("instruction").innerHTML = xmlDoc.getElementsByTagName("html_instructions")[0].childNodes[0].nodeValue;
            
            } else {
                alert ("Position stimmt mit Ziel überein!");
                alert("Jetzt wird der "+nextSpot+". Beitrag abgespielt.");
                
                if (nextSpot == <?php echo $lastSpot; ?>) {
                    alert("die Routenführung ist beendet!");
                } else {
                    nextSpot = nextSpot + 1;
                    clearWatch(watchId);
                    start_guide(nextSpot);
                }
            }
        }
        
        function watchError(error) {
            console.warn('ERROR(' + error.code + '): ' + error.message);
        }
        
        var geolocationOptions = {
            enableHighAccuracy: false,
            timeout: 20000,
            maximumAge: 0
        };
        
        // eigentliche Funktion der Routenführung und Beginn der Schleife, die von Spot zu Spot navigiert
        function start_guide(nextSpot) {
            alert("Wir sind bei"+nextSpot);
            document.getElementById("spotDest").innerHTML = "Nächster Spot bei "+spotsLatitude[nextSpot]+" , "+spotsLongitude[nextSpot];
            watchId = navigator.geolocation.watchPosition(function(position) {
                watchSuccess(position, nextSpot);
            }, watchError, geolocationOptions);
        }
    
        
        // Eine einmalige Standort-Abfrage wird durchgeführt, um die Zugriffsberechtigung gezielt abzufragen >> anschliessend startet die Routenführung (Funktion)
         navigator.geolocation.getCurrentPosition(function(position){
             // alert("Position konnte erfolgreich ermittelt werden.");
             var request;
             start_guide(1);
         }, function() {
            alert("deine Position konnte leider nicht ermittelt werden.");
        });
        
    </script>
    
</body>
</html>