<?php
    require_once('../system/config.php');              // config-Datei einbinden

    if(isset($_GET['id'])) {
    // die per GET (in der URL) übergebene id wird als $route_id gespeichert und die Details zur entsprechenden Route abgefragt
    $route_id = $_GET['id'];
    $route = mysqli_fetch_array(get_route_details($route_id));
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

?>


<!--------------------------- HTML --------------------------->

<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - route</title>
    
</head>

<body>

    <h1><?php echo $route['name'];?></h1>
    <img src ="..<?php echo $route['image'];?>">
    <p><?php echo $route['description'];?></p>
    <p><?php echo $route['durance_minutes'];?> Minuten</p>
    <p><?php echo $route['distance_meter'];?> Meter</p>
    <a href = "start.php">Route starten</a>
    
    
</body>
</html>
