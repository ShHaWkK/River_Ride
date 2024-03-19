<?php

$servername = "localhost";
$dbname = "river_ride"; // Mettez ici le nom de votre base de données
$username = "root";
$password = "root";

try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}

