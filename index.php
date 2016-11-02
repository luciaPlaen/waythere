<?php
    require_once('./system/config.php');              // config-Datei einbinden
?>


<!--------------------------- HTML --------------------------->

<!doctype html>

<html lang="de">
    
<head>
    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>waythere stories</title>
    
    <!-- Links -->
    <link href="../style/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="style/style.css" rel="stylesheet" type="text/css">
    
</head>
    
    
<body>
    
    <h1>waythere stories </h1>
    
    <div>
        
        <?php
            $all_routes = get_all_routes();
            while ($line = mysqli_fetch_array($all_routes)) {
            // Anfang der Schleife, die aus jeder Ergebniszeile einen eigenen Routen-Eintrag generiert
        ?>
        
                <a href="app/route.php?id=<?php echo $line['route_id']; ?>"><?php echo $line['name'];?></a>
                <br>
                <p><?php echo $line['description']; ?></p>
                <br>
        
        <?php
            }           // Ende der Schleife
        ?>

    </div>

</body>
</html>
