<?php

    // Hier wird  der von guide.php zusammengestellte und übergebene http-Request ausgeführt und das empfangene xml-File an zurückgegeben

        $apiRequest = $_GET['url']."&destination=".$_GET['destination']."&mode=".$_GET['mode']."&language=".$_GET['language']."&key=".$_GET['key'];    

         $xml = file_get_contents($apiRequest);
         echo $xml;

?>