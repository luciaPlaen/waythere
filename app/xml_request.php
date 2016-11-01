<?php

         $positionLatitude = $_GET['posLatitude'];
         $positionLongitude = $_GET['posLongitude'];
    
         $apiSettings = get_api_settings();
    
         $apiRequest = "https://maps.googleapis.com/maps/api/directions/xml?origin=".$positionLatitude.",".$positionLongitude."&destination=".$destinationLatitude.",".$destinationLongitude."&mode=".$apiSettings[1]."&language=".$apiSettings[2]."&key=".$apiSettings[0];
    
         $xml = file_get_contents($apiRequest);
         return $xml;

?>