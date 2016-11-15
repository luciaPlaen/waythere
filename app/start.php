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
    <link href="../style/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../style/style.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <title>waythere stories - start</title>
    
</head>

<body>
    
    <h1> Bevor dem Start ... </h1>
    <br>
    <p>Prüfe den Akku deines Smartphones!</p>
    <p>Lege deine Kopfhörer an!</p>
    <br>
    <a href = "guide.php?id=<?php echo $route_id; ?>">UND LOS! (Routenführung starten)</a>
    <br>
    <br>
    <a href = "funktionstest.php?id=<?php echo $route_id; ?>">Funktionen testen</a>
    <br>
    <a href ="route.php">abbrechen</a>
    
</body>
</html>