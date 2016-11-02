<?php
    
        $apiRequest = "https://maps.googleapis.com/maps/api/directions/xml?origin=46.8536741,9.5143009&destination=46.9475,7.45137&mode=walking&language=de&key=AIzaSyBtXwlLuDB7czfcUqNaeBACF2RIdBHZaiE";
         $xml = file_get_contents($apiRequest);
         echo $xml;

?>