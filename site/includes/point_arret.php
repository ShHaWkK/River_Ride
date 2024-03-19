<?php
session_start();
try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}


// Récupérer le point d'arrêt
$query = $db->prepare('SELECT * FROM points_arret WHERE id = ?');
$query->execute([$_GET['id']]);
$pointArret = $query->fetch(PDO::FETCH_ASSOC);

// Récupérer les hébergements pour ce point d'arrêt
$query = $db->prepare('SELECT * FROM hebergements WHERE point_arret_id = ?');
$query->execute([$pointArret['id']]);
$hebergements = $query->fetchAll(PDO::FETCH_ASSOC);

// Afficher les détails du point d'arrêt et la liste des hébergements
echo "<h1>" . $pointArret['nom'] . "</h1>";
echo "<p>" . $pointArret['description'] . "</p>";

echo "<h2>Hébergements disponibles</h2>";
foreach ($hebergements as $hebergement) {
    echo "<h3>" . $hebergement['nom'] . "</h3>";
    echo "<p>" . $hebergement['description'] . "</p>";
    echo "<p>Prix: " . $hebergement['prix'] . " €</p>";
    echo "<form method='POST' action='/reserver.php'>";
    echo "<input type='hidden' name='hebergement_id' value='" . $hebergement['id'] . "'>";
    echo "<input type='submit' value='Réserver'>";
    echo "</form>";
}
