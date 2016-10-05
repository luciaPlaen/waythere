<?php
    require_once('../system/config.php');              // config-Datei einbinden

    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert
    $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

    $position = 1;      // $position beschreibt die aktuell nächste Station einer Route - das nächste Ziel der Routenführung

    $next_coordinates = get_coordinates($route_id, $position);
    $next_destination = mysqli_fetch_array( $next_coordinates, MYSQLI_NUM);
    echo "destination latitude: ".$next_destination[0]."<br>"."destination longitude: ".$next_destination[1];

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
    
        <a href ="route.php">abbrechen</a>
    
</body>
</html>


                         https://maps.googleapis.com/maps/api/directions/xml?origin=46.852839,9.513134&destination={latitude,longitude}&mode={travel_mode}&language={language}&key={our_key}