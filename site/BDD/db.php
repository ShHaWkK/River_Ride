<?php
try 
{
	$conn = new PDO('mysql:host=VOTRE_ADRESSE_IP;dbname=gymlight', '', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}
?>
