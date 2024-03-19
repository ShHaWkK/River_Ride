
<?php
try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=river_ride', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}
?>
