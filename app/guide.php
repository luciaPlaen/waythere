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

    ?>



<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="../style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - routenführung</title>
    
</head>

<body>
    
    <h1> Routenführung </h1>
    
    <div id = "xml">leer</div>
    <p id = "posLat">posLat</p>
    <p id = "posLong">posLong</p>
    
    <script>

        navigator.geolocation.getCurrentPosition(function(position){ 
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("posLat").innerHTML = positionLatitude;
            document.getElementById("posLong").innerHTML = positionLongitude;
        }, function() {
            document.getElementById("posLat").innerHTML = "deine Position konnte leider nicht ermittelt werden.";
            document.getElementById("posLong").innerHTML = " ";
        });
        
        /*
        document.getElementById("xml").innerHTML = "xml_request.php?id=<?php echo $route_id; ?>posLatitude=" + positionLatitude + "&posLongitude=" + positionLongitude;
        
            function xml_request() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("xml").innerHTML = "gesendet!";
                        //var xml =this.responseText;
                        document.getElementById("xml").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", url, true);
                xhttp.send();
            } 
        
            xml_request();
            */
        
    </script>
    
    
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>