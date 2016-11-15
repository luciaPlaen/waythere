<?php
    require_once('../system/config.php');              // config-Datei einbinden


    if(isset($_GET['id'])) {
        // die per GET (in der URL) übergebene id wird als $route_id gespeichert
        $route_id = $_GET['id'];
    } else header("Location:../index.php");     // ansonsten wird der User zurück auf die Routenübersicht verwiesen

    $lastSpot = count_spots($route_id);           // Abfragen des letzten Spots der Route, um später zu wissen, wie viele Durchläufe die Navigation erfordert
    $spotCount = 1;                                          // Initialisierung eines entsprechenden Spot-Zählers
    $api_settings = get_api_settings();             //  Abfragen von in der Datenbank gespeicherten Informationen für die Google API
?>



<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="../style/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>waythere stories - guide</title>
    
</head>

<body>
    
    <h1> Routenführung </h1>
    <br>
    <p id = "spotDest"></p>
    <p id = "spotTitel"></p>
    <br>
    <p id = "preinstruction"></p>
    <button id = "ok" hidden>Ja, kann losgehen!</button>
    <p id = "posLat"></p>
    <p id = "posLong"></p>
    <br>
    <p id = "instruction"></p>
    <audio id = "audio" autoplay = "autoplay"></audio>
    <br>
    <a href ="route.php">abbrechen</a>
    
    
    <script>
        
        // Abfragen der benötigten Informationen jedes zur Route gehörigen Spots >> Speichern der Werte in Arrays
        // der Index entspricht der Einfachheit halber jeweils der Spot-Nummerierung (dem Array wird deshalb der Anfangswert (0) mitgegeben)
        var spotsLatitude = [0];
        var spotsLongitude = [0];
        var spotsTitel = [0];
        var spotsPreInstructions = [0];
        var spotsMediaFilePath = [0];
        <?php
            $all_spot_informations = get_all_spot_informations($route_id);
            while ($line = mysqli_fetch_array($all_spot_informations, MYSQLI_ASSOC)) { ?>
                spotsLatitude.push(<?php echo $line['latitude']; ?>);
                spotsLongitude.push(<?php echo $line['longitude']; ?>);
                spotsTitel.push("<?php echo $line['spot_titel']; ?>");
                spotsPreInstructions.push("<?php echo $line['pre_instruction']; ?>");
                spotsMediaFilePath.push("<?php echo $line['file_name']; ?>");
        <?php
            } ?>
        
        // Definition der Success-Funktion für watchPosition >> eigentlicher Navigations-Ablauf
        function watchSuccess(position, nextSpot, destLatPlus, destLatMinus, destLongPlus, destLongMinus, watchId) {
            // die aktuelle Position wird abgefragt, in Variabeln gespeichert und ausgegeben
            var positionLatitude = position.coords.latitude;
            var positionLongitude = position.coords.longitude;
            document.getElementById("posLat").innerHTML = "PositionLatitude: "+positionLatitude;
            document.getElementById("posLong").innerHTML = "PositionLongitude: "+positionLongitude;
            // console.log("Position wurde aktualisiert zu: Latitude: "+positionLatitude+" & Longitude: "+positionLongitude);
            // der http-Request für Google Maps wird mit den dynamischen Informationen bestückt und zu einer Variable zusammengestellt
            request = "https://maps.googleapis.com/maps/api/directions/xml?origin="+positionLatitude+","+positionLongitude+"&destination="+spotsLatitude[nextSpot]+","+spotsLongitude[nextSpot]+"&mode=<?php echo $api_settings[1]; ?>&language=<?php echo $api_settings[2]; ?>&key=<?php echo $api_settings[0]; ?>";
            // console.log(request);
            // der Request wird erstellt :
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // bei Erfolg des Requests wird das in Form eines Strings zurückgelieferte xml-File abgespeichert ...
                    var xml =this.responseText;
                    // console.log("XML-Datei empfangen!");
                    // ... und geparst
                    parser = new DOMParser();
                    xmlDoc = parser.parseFromString(xml,"text/xml");    
                }
            };
            xhttp.open("GET", "xml_request.php?url="+request, false);       // xml_request.php verarbeitet den Request an Google
            xhttp.send();
            
            // Vergleich der aktuellen Position mit den Koordinaten des nächsten Spot-Zieles :
            if (positionLatitude < destLatPlus && positionLatitude > destLatMinus && positionLongitude < destLongPlus && positionLongitude > destLongMinus) {
                // Wenn die aktuelle Position innerhalb des Ziel-Pufferbereiches liegt wird die watchID gelöscht und die sichtbaren Informationen aktualisiert
                navigator.geolocation.clearWatch(watchId);
                // alert ("Position stimmt mit Ziel überein!");
                document.getElementById("spotDest").innerHTML = "";
                document.getElementById("spotTitel").innerHTML = spotsTitel[nextSpot];
                document.getElementById("preinstruction").innerHTML = "Ziel erreicht! <br>"+spotsPreInstructions[nextSpot];
                document.getElementById("ok").removeAttribute("hidden");
                document.getElementById("posLat").innerHTML = "";
                document.getElementById("posLong").innerHTML = "";
                document.getElementById("instruction").innerHTML = "";
                // ein Button "ok" wird eingeblendet, durch den der User sich für den Beitrag bereit erklären kann (als Bestätigung nach der Pre-Instruction)
                document.getElementById("ok").onclick = function() {
                    
                    // der Datei-Pfad für den richtigen Audio-Beitrag wird zusammengestellt
                    var audioFilePath = "../files/audio_inputs/"+spotsMediaFilePath[nextSpot];
                    // das bereits vorbereitete HTML-Element wird mit der richtigen src versehen und erneut geladen (autoplay)
                    document.getElementById("audio").src = audioFilePath;
                    document.getElementById("audio").load();
                
                    // Abfangen des Events bei Abspiel-Ende des Audio-Beitrages
                    document.getElementById("audio").onended = function() {
                        // alert("Audio fertig !");
                        
                        // Abfrage, ob der gerade durchgespielte Spot der letzte der Route war
                        if (nextSpot == <?php echo $lastSpot; ?>) {
                            alert("die Routenführung ist beendet!");
                        } else {        // wenn nein, werden die Anzeige-Informationen wiederhergestellt
                            document.getElementById("spotTitel").innerHTML = "";
                            document.getElementById("preinstruction").innerHTML = "";
                            document.getElementById("ok").setAttribute("hidden");
                            // der Spot-Zähler wird aktualisiert
                            nextSpot = nextSpot + 1;
                            // und die Routenführung zum nächsten Spot aufgerufen / gestartet
                            start_guide(nextSpot);
                        }
                    }   // Ende Abspiel-Ende-Event
                }   // Ende Audio-Beitrag-Abspielen
            
            } else {      // wenn die aktuelle Position noch NICHT mit den Spot-Ziel-Koordinaten übereinstimmt
                // console.log ("Position stimmt nicht mit Ziel überein!");
                document.getElementById("instruction").innerHTML = "In "+xmlDoc.getElementsByTagName("value")[1].childNodes[0].nodeValue+" Metern : <br>"+xmlDoc.getElementsByTagName("html_instructions")[0].childNodes[0].nodeValue;
            }
        }       // Ende der Success-Funktion

        // Definition der Error-Funktion für watchPosition >> Ausgabe einer Fehlermeldung
        function watchError(error) {
            console.warn('ERROR(' + error.code + '): ' + error.message);
        }
        
        // Definition der Options für watchPosition
        var geolocationOptions = {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
        };
        
        // Start der Routenführung zu einem bestimmten Spot
        function start_guide(nextSpot) {
            // alert("Beginn der Routenführung zu Spot "+nextSpot);
            document.getElementById("spotDest").innerHTML = "Nächster Spot bei "+spotsLatitude[nextSpot]+" , "+spotsLongitude[nextSpot];
            // Definition der Pufferbereiche rund um die Ziel- / Spot-Koordinaten
            var destLatPlus = spotsLatitude[nextSpot] + 0.0003;
            var destLatMinus = spotsLatitude[nextSpot] - 0.0003;
            var destLongPlus = spotsLongitude[nextSpot] + 0.0003;
            var destLongMinus = spotsLongitude[nextSpot] - 0.0003;
            // alert ("Die Pufferzone wurde zwischen : "+destLatPlus+" ; "+destLatMinus+" ; "+destLongPlus+" ; "+destLongMinus+" festgelegt");
            
            // Start von watchPosition >> alle erforderlichen Informationen (aktuelle Position, Ziel-Pufferbereiche, aktueller Spot) werden der Success-Funktion mitgegeben
            var watchId = navigator.geolocation.watchPosition(function(position) {
                watchSuccess(position, nextSpot, destLatPlus, destLatMinus, destLongPlus, destLongMinus, watchId);
            }, watchError, geolocationOptions);
        }
    
        
        // Eine einmalige Standort-Abfrage wird durchgeführt, um die Zugriffsberechtigung gezielt abzufragen >> anschliessend startet die Routenführung (Funktion)
         navigator.geolocation.getCurrentPosition(function(position){
             // alert("Position konnte erfolgreich ermittelt werden.");
             var request;
             start_guide(1);        // Aufruf / Start der Routenführung zum ersten Spot
         }, function() {
            alert("deine Position konnte leider nicht ermittelt werden.");
        });
        
    </script>
    
</body>
</html>