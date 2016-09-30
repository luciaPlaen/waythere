<?php

/******************************************************************************************************************
data.php ist eine Sammlung aller wiederkehrend verwendeter Funktionen, was den Verwaltungsaufwand erheblich verringert.
******************************************************************************************************************/

// Verbindung zur Datenbank:

function get_db_connection() {
		$db = mysqli_connect('web.fh-htwchur.ch/', '555712_3_1', 'bWvrijhZ@1cI', '555712_3_1')         //>> (server, user, pw, db)
 		 or die('Verbindung mit Datenbank-Server fehlgeschlagen.');
  		mysqli_query($db, "SET NAMES 'utf8'");
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
    $sql = "SELECT route.route_id, route.name, route.image, route.description";
    $sql .= "FROM Route";
    $sql .= ";";
    
    $result = get_result($sql);
    return $result;
}
?>