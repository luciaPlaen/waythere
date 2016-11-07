<?php
    require_once('../system/config.php');              // config-Datei einbinden


    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert
    $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

    $lastSpot = count_spots($route_id);
    $nextSpot = 1;
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
    
    <script>
        
        
        // Abfragen der Koordinaten jedes zur Route gehörigen Spots >> Speichern der Werte in 2 Arrays (Latitude & Longitude) >> der Index entspricht jeweils der Spot-Nummerierung (dem Array wird der einfacheren Handhabung halber ein Anfangswert (0) mitgegeben, um diesen Umstand zu erreichen)
        var destinationLatitude = [0];
         var destinationLongitude = [0];
        for (spot = 1; spot <= <?php echo $lastSpot; ?>; spot++){
            <?php
                $destination = mysqli_fetch_array(get_spot_coordinates($route_id, $nextSpot), MYSQLI_ASSOC);
                $nextSpot = $nextSpot + 1;
            ?>
            destinationLatitude.push(<?php echo $destination['latitude']; ?>);
            destinationLongitude.push(<?php echo $destination['longitude']; ?>);
        }
        document.getElementById("testing").innerHTML = "Lat = "+destinationLatitude+"Long = "+destinationLongitude;
        //start_guide(1);
        
        
        
        function start_guide(nextSpot) {
            //for (spot = 1; spot <= <?php //echo $lastSpot; ?>; spot++){}
            
            alert("Wir sind bei"+nextSpot);
            
            if (nextSpot == <?php echo $lastSpot; ?>) {
                alert("die Routenführung ist beendet!");
            } else {
                nextSpot = nextSpot + 1;
                start_guide(nextSpot);
            }
        }
    
        
        // Eine einmalige Standort-Abfrage wird durchgeführt, um die Zugriffsberechtigung gezielt abzufragen >> anschliessend startet die Routenführung (Funktion)
         navigator.geolocation.getCurrentPosition(function(position){
             document.getElementById("geolocation_error").innerHTML = "alles bestens - starte jetzt die Routenführung.";
             start_guide(1);
         }, function() {
            document.getElementById("geolocation_error").innerHTML = "deine Position konnte leider nicht ermittelt werden.";
        });
        
        
        
        
        
        /*
        function instruction() {
            var step_counter = 0;
            document.getElementById("xml").innerHTML = xmlDoc.getElementsByTagName("html_instructions")[step_counter].childNodes[step_counter].nodeValue;        
            
        }
        
        
        function guide() {

            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("destLat").innerHTML = "<?php //echo $destinationLatitude; ?>";
            document.getElementById("destLong").innerHTML = "<?php //echo $destinationLongitude; ?>";
            document.getElementById("posLat").innerHTML = positionLatitude;
            document.getElementById("posLong").innerHTML = positionLongitude;
            
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
    
        }
        */
        
    </script>
    
    
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>