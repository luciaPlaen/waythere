
<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>
    
    <!-- Links -->
    <link href="../style/style.css" rel="stylesheet" type="text/css">

    <meta charset="utf-8">
    <title>test standort-ausgabe</title>
    
</head>

<body>
    
    <h1> Standort-Test </h1>

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
    
</body>
</html>