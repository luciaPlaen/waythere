<?php
    require_once('../system/config.php');              // config-Datei einbinden

    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert
    $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

        
        $nextSpot = 1;      // beschreibt die aktuell nächste Station einer Route - das nächste Ziel der Routenführung
        $destination = mysqli_fetch_array(get_coordinates($route_id, $nextSpot), MYSQLI_ASSOC);
        $destinationLatitude = $destination['latitude'];
        $destinationLongitude = $destination['longitude'];
    
        $apiSettings = get_api_settings();
        $apiRequest = "https://maps.googleapis.com/maps/api/directions/xml?origin=".$destinationLatitude.",".$destinationLongitude."&destination=".$destinationLatitude.",".$destinationLongitude."&mode=".$apiSettings[1]."&language=".$apiSettings[2]."&key=".$apiSettings[0];
    
    ?>



<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - routenführung</title>
    
</head>

<body>
    
    <h1> Routenführung </h1>
    
    <div id = "xml">leer</div>
    <button type="button" onclick="loadXML(request)">Get XML</button>
    
    <script>
        
        navigator.geolocation.getCurrentPosition(function(position){ 
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            });
        
        var request = "<?php echo $apiRequest; ?>";
        
        
        function loadXML(url) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("xml").innerHTML = this.responseText;
                    document.getElementById("xml").innerHTML = "fertig!";
                }
            };
            xhttp.open("GET", url, true);
            xhttp.send();
        } 
        
    </script>
    
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>