<?php

/******************************************************************************************************************
data.php ist eine Sammlung aller wiederkehrend verwendeter Funktionen, was den Verwaltungsaufwand erheblich verringert.
******************************************************************************************************************/


// Verbindung zur Datenbank:
function get_db_connection() {
		$db = mysqli_connect('beburkar.mysql.db.internal', 'beburkar_bealu', 'x1tbv5Bq', 'beburkar_waythere')         //>> (server, user, pw, db)
 		 or die('Verbindung mit Datenbank-Server fehlgeschlagen.');
  		//mysqli_query($db, "SET NAMES 'utf8'");
		return $db;
}
	

// Ausführung der Anweisung $sql, Rückgabe des Ergebnisses als $result und Schliessen der Datenbank :
function get_result($sql) {
		$db = get_db_connection();
		$result = mysqli_query($db, $sql);
		mysqli_close($db);
		return $result;
}


// Abfragen der Liste aller verfügbaren Routen >> id, name, image, description
function get_all_routes() {
    
    $sql = "SELECT Route.route_id, Route.name, Route.image, Route.description, Route.creating_date FROM Route ";
    $sql .= "ORDER BY Route.creating_date DESC;";
    
    return get_result($sql);
}

// Abfragen der Details einer Route >> name, image, description, durance_minutes, distance_meter
function get_route_details($id) {

    $sql = "SELECT Route.name, Route.description, Route.image, Route.durance_minutes, Route.distance_meter FROM Route ";
    $sql .= "WHERE Route.route_id = $id;";
    
    return get_result($sql);
}

// Abfragen der Tags zu einer Route
function get_route_tags($id) {
    
    $sql = "SELECT Tag.tag_name FROM Tag, taged ";
    $sql .= "WHERE taged.route_id = $id AND taged.tag_id = Tag.tag_id;";
    
    return get_result($sql);
}

function count_spots($route_id){
    $sql = "SELECT COUNT(belongsto.spot_id) FROM belongsto WHERE belongsto.route_id = $route_id;";
    $count = mysqli_fetch_array(get_result($sql));
    return $count[0]; 
}

// Abfragen der Koordinaten eines Spots
function get_spot_coordinates($route_id, $position) {
    
    $sql = "SELECT Spot.latitude, Spot.longitude FROM Spot, belongsto ";
    $sql .= "WHERE belongsto.route_id = $route_id AND belongsto.spot_id = Spot.spot_id AND belongsto.number = $position;";
    return get_result($sql);
}

// Abfrage der Koordinaten aller Spots einer Route
function get_all_spot_coordinates($route_id) {
    
    $sql = "SELECT Spot.latitude, Spot.longitude FROM Spot, belongsto ";
    $sql .= "WHERE belongsto.route_id = $route_id AND belongsto.spot_id = Spot.spot_id ";
    $sqli .= "ORDER BY belongsto.number;";
    return get_result($sql);
}

// Abfragen der Spezifikationen für die Google API
function get_api_settings() {
    $sql_key = "SELECT Api_specs.content FROM Api_specs WHERE Api_specs.spec_id = 1;";
    $specs_key = mysqli_fetch_array(get_result($sql_key), MYSQLI_NUM);
    $sql_mode = "SELECT Api_specs.content FROM Api_specs WHERE Api_specs.spec_id = 2;";
    $specs_mode = mysqli_fetch_array(get_result($sql_mode), MYSQLI_NUM);
    $sql_language = "SELECT Api_specs.content FROM Api_specs WHERE Api_specs.spec_id = 3;";
    $specs_language = mysqli_fetch_array(get_result($sql_language), MYSQLI_NUM);
    $specs = [$specs_key[0], $specs_mode[0], $specs_language[0]];
    return $specs;
}

// Struktur des Google API HTTP Requests :
// https://maps.googleapis.com/maps/api/directions/xml?origin=46.852839,9.513134&destination={latitude,longitude}&mode={travel_mode}&language={language}&key={our_key}

?>