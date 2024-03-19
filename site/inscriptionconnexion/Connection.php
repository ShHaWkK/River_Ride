<?php
try 
{
	$conn = new PDO('mysql:host=TON ADRESSE;dbname=river_ride', 'null', 'null', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}
?>
