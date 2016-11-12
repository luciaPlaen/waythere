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
    <p id = "spotTitel"></p>
    <br>
    <p id = "preinstruction"></p>
    <p id = "posLat"></p>
    <p id = "posLong"></p>
    <br>
    <p id = "instruction"></p>
    <br>
    <a href ="route.php">abbrechen</a>
    
    
    <script>
        
        
        // Abfragen der Informationen jedes zur Route gehörigen Spots >> Speichern der Werte in Arrays (Latitude & Longitude & Titel & Pre-instruction) >> der Index entspricht jeweils der Spot-Nummerierung (dem Array wird deshalb ein Anfangswert (0) mitgegeben)
        var spotsLatitude = [0];
        var spotsLongitude = [0];
        var spotsTitel = [0];
        var spotsPreInstructions = [0];
        
        <?php
            $all_spot_informations = get_all_spot_informations($route_id);
            while ($line = mysqli_fetch_array($all_spot_informations, MYSQLI_ASSOC)) { ?>
                spotsLatitude.push(<?php echo $line['latitude']; ?>);
                spotsLongitude.push(<?php echo $line['longitude']; ?>);
                spotsTitel.push("<?php echo $line['spot_titel']; ?>");
                spotsPreInstructions.push("<?php echo $line['pre_instruction']; ?>");
        <?php
            } ?>
        
        
        function watchSuccess(position, nextSpot, destLatPlus, destLatMinus, destLongPlus, destLongMinus, watchId) {
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
            
           // if (positionLatitude == spotsLatitude[nextSpot]) {      // if-Bedingugng anpassen, sodass ein Puffer berücksichtigt wird
            // if (positionLatitude < spotsLatitude[nextSpot]+0.0003 && positionLatitude > spotsLatitude[nextSpot]-0.0003 && positionLongitude < spotsLongitude[nextSpot]+0.0003 && positionLongitude > spotsLongitude[nextSpots]-0.0003) {
            
            if (positionLatitude < destLatPlus && positionLatitude > destLatMinus && positionLongitude < destLongPlus && positionLongitude > destLongMinus) {
                
                navigator.geolocation.clearWatch(watchId);
                alert ("Position stimmt mit Ziel überein!");
                document.getElementById("spotDest").innerHTML = "";
                document.getElementById("spotTitel").innerHTML = spotsTitel[nextSpot];
                document.getElementById("preinstruction").innerHTML = spotsPreInstructions[nextSpot];
                document.getElementById("posLat").innerHTML = "";
                document.getElementById("posLong").innerHTML = "";
                document.getElementById("instruction").innerHTML = "";

                alert("Jetzt wird der "+nextSpot+". Beitrag abgespielt.");
                // Richtige / Tatsächliche Audio-Ausgabe
                
                // Abfangen des Events bei Abspiel-Ende des Audio-Beitrages ...
                
                if (nextSpot == <?php echo $lastSpot; ?>) {
                    alert("die Routenführung ist beendet!");
                } else {
                    document.getElementById("spotTitel").innerHTML = "";
                    document.getElementById("preinstruction").innerHTML = "";
                    nextSpot = nextSpot + 1;
                    start_guide(nextSpot);
                }
            
            } else {
                console.log ("Position stimmt nicht mit Ziel überein!");
                document.getElementById("instruction").innerHTML = xmlDoc.getElementsByTagName("html_instructions")[0].childNodes[0].nodeValue;
            }
        }
        
        function watchError(error) {
            console.warn('ERROR(' + error.code + '): ' + error.message);
        }
        
        var geolocationOptions = {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
        };
        
        // eigentliche Funktion der Routenführung und Beginn der Schleife, die von Spot zu Spot navigiert
        function start_guide(nextSpot) {
            // alert("Wir sind bei"+nextSpot);
            document.getElementById("spotDest").innerHTML = "Nächster Spot bei "+spotsLatitude[nextSpot]+" , "+spotsLongitude[nextSpot];
            
            var destLatPlus = spotsLatitude[nextSpot] + 0.0003;
            var destLatMinus = spotsLatitude[nextSpot] - 0.0003;
            var destLongPlus = spotsLongitude[nextSpot] + 0.0003;
            var destLongMinus = spotsLongitude[nextSpot] - 0.0003;
            // alert ("Die Pufferzone wurde zwischen : "+destLatPlus+" ; "+destLatMinus+" ; "+destLongPlus+" ; "+destLongMinus+" festgelegt");
            
            var watchId = navigator.geolocation.watchPosition(function(position) {
                watchSuccess(position, nextSpot, destLatPlus, destLatMinus, destLongPlus, destLongMinus, watchId);
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