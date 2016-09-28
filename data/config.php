<?php

/******************************************************************************************************************
config.php wird in allen Dateien aufgerufen, weshalb hier alle weiteren benötigten Dateien eingebunden werden.
******************************************************************************************************************/

	require_once('data.php');              // in data.php sind alle wiederholt notwendigen Funktionen vorprogrammiert

	// Definition der Konstanten
	define("SERVER_ROOT", "http://".$_SERVER['SERVER_NAME']);
	define("SITE_PATH", "waythere");
	define("SITE_ROOT", SERVER_ROOT . SITE_PATH);
?>