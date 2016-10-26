<?php
    require_once('../system/config.php');              // config-Datei einbinden

    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert
    $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

?>



<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - start</title>
    
</head>

<body>
    
    <h1> Start! </h1>
    
        <?php
            $nextSpot = 1;
    
            $destination = mysqli_fetch_array(get_coordinates($route_id, $nextSpot), MYSQLI_ASSOC);
            echo "destination latitude: ".$destination['latitude']."<br>"."destination longitude: ".$destination['longitude'];
            $api_settings = get_api_settings();
        ?>
    
        <p>https://maps.googleapis.com/maps/api/directions/xml?origin=46.852839,9.513134&destination=<?php echo $destination[0]; ?>,<?php echo $destination[1]; ?>&mode=<?php echo $api_settings[1]; ?>&language=<?php echo $api_settings[2]; ?>&key=<?php echo $api_settings[0]; ?></p>
    
    <div id="pos">
        Deine Position wird ermittelt...
    </div>
    
    <script>

        navigator.geolocation.getCurrentPosition(function(position){ 
            document.getElementById('pos').innerHTML = 'Latitude: '+position.coords.latitude+' / Longitude: '+position.coords.longitude;
        }, function(){
                document.getElementById('pos').innerHTML = 'Deine Position konnte leider nicht ermittelt werden';
            });
    
    </script>
    
    <br>
    <a href = "guide.php?id=<?php echo $route_id; ?>">Routenführung starten</a>
    
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>