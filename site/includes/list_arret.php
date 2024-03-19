
<?php
session_start(); 




try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}



// Afficher la liste des points d'arrêt
foreach ($pointsArret as $pointArret) {
    echo "<a href='/point_arret.php?id=" . $pointArret['id'] . "'>" . $pointArret['nom'] . "</a><br>";
}