<?php

/******************************************************************************************************************
data.php ist eine Sammlung aller wiederkehrend verwendeter Funktionen, was den Verwaltungsaufwand erheblich verringert.
******************************************************************************************************************/


// Verbindung zur Datenbank:
function get_db_connection() {
		$db = mysqli_connect('localhost', '555712_3_1', 'bWvrijhZ@1cI', '555712_3_1')         //>> (server, user, pw, db)
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

?>