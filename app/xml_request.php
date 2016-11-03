<?php
        
        $apiRequest = $_GET['url']."&destination=".$_GET['destination']."&mode=".$_GET['mode']."&language=".$_GET['language']."&key=".$_GET['key'];    

         $xml = file_get_contents($apiRequest);
         echo $xml;

?>