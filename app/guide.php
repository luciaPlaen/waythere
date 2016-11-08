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
    <p id = "geolocation_error"></p>
    <p id = "testing"></p>
    <p id = "posLat">posLat</p>
    <p id = "posLong">posLong</p>
    <p id = "destLat">destLat</p>
    <p id = "destLong">destLong</p>
    <p id = "request">http-request ...</p>
    <p id = "xml">xml ...</p>
    
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
        document.getElementById("testing").innerHTML = "Lat = "+spotsLatitude+"Long = "+spotsLongitude;
        
        
        function watchSuccess(position, nextSpot) {
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("posLat").innerHTML = positionLatitude;
            document.getElementById("posLong").innerHTML = positionLongitude;
            console.log("Position wurde aktualisiert zu: Latitude: "+positionLatitude+" & Longitude: "+positionLongitude);

            if (positionLatitude != spotsLatitude[nextSpot]) {
                console.log ("Position stimmt nicht mit Ziel überein!");
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
        
        // eigentliche Funktion der Routenführung und Beginn der Schleife, die von Spot zu Spot navigiert
        function start_guide(nextSpot) {
            alert("Wir sind bei"+nextSpot);
            document.getElementById("destLat").innerHTML = spotsLatitude[nextSpot];
            document.getElementById("destLong").innerHTML = spotsLatitude[nextSpot];
            watchId = navigator.geolocation.watchPosition(function(position) {
                watchSuccess(position, nextSpot);
            }, watchError);
        }
    
        
        // Eine einmalige Standort-Abfrage wird durchgeführt, um die Zugriffsberechtigung gezielt abzufragen >> anschliessend startet die Routenführung (Funktion)
         navigator.geolocation.getCurrentPosition(function(position){
             alert("Position konnte erfolgreich ermittelt werden.");
             start_guide(1);
         }, function() {
            document.getElementById("geolocation_error").innerHTML = "deine Position konnte leider nicht ermittelt werden.";
        });
        
        
        
        
        /*
        function instruction() {
            var step_counter = 0;
            document.getElementById("xml").innerHTML = xmlDoc.getElementsByTagName("html_instructions")[step_counter].childNodes[step_counter].nodeValue;        
         }
            
            var apiRequest = "https://maps.googleapis.com/maps/api/directions/xml?origin="+positionLatitude+","+positionLongitude+"&destination=<?php //echo $destinationLatitude; ?>,<?php //echo $destinationLongitude; ?>&mode=<?php //echo $api_settings[1]; ?>&language=<?php //echo $api_settings[2]; ?>&key=<?php //echo $api_settings[0]; ?>";
            document.getElementById("request").innerHTML = apiRequest;
            

            var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var xml =this.responseText;
                        console.log(xml);
                        // document.getElementById("xml").innerHTML = "empfangen!";
                        
                        parser = new DOMParser();
                        xmlDoc = parser.parseFromString(xml,"text/xml");

                        instruction();
                        
                    }
                };
                xhttp.open("GET", "xml_request.php?url="+apiRequest, true);
                xhttp.send();

        */
        
    </script>
    
</body>
</html>