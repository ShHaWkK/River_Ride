<?php


try 
{
	$conn = new PDO('mysql:host=;dbname=gymlight', '', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}
}
?>