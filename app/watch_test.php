

<!--------------------------- HTML --------------------------->


<!doctype html>

<html>
<head>

    <meta charset="utf-8">
    <title>waythere stories - routenführung</title>
    
</head>

<body>
    
    <h1> Test: WatchPosition </h1>
    
    <p id = "posLat">posLat</p>
    <p id = "posLng">posLng</p>
    <p id = "tarLat">tarLat</p>
    <p id = "tarLng">tarLng</p>

<script>

    document.getElementById('posLat').innerHtml = "hi";
    
function success(pos) {
  var crd = pos.coords;
    document.getElementById("posLat").innerHtml = pos.latitude;
    document.getElementById("posLng").innerHtml = pos.longitude;

  if (target.latitude === crd.latitude && target.longitude === crd.longitude) {
    console.log('Congratulations, you reached the target');
    navigator.geolocation.clearWatch(watchId);
  }
}

function error(err) {
  console.warn('ERROR(' + err.code + '): ' + err.message);
}

var target = {
  latitude : 0,
  longitude: 0
};
    document.getElementById("tarLat").innerHtml = "target.latitude = " + target.latitude;
    document.getElementById("tarLng").innerHtml = "target.longitude = " + target.longitude;

var options = {
  enableHighAccuracy: false,
  timeout: 5000,
  maximumAge: 0
};

var watchId = navigator.geolocation.watchPosition(success, error, options);

</script>
    
</body>
</html>