<?php

session_start(); 




try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}



// Vérifiez si l'utilisateur est connecté et récupérez son id
// Ceci est un exemple, vous devriez utiliser une méthode plus sécurisée pour gérer les sessions utilisateurs
$userId = $_SESSION['user_id'];

// Récupérer tous les points d'arrêt
$query = $db->query('SELECT * FROM points_arret');
$pointsArret = $query->fetchAll();

// Pour chaque point d'arrêt, récupérez les hébergements disponibles
foreach ($pointsArret as $pointArret) {
    $query = $db->prepare('SELECT * FROM hebergements WHERE point_arret_id = ?');
    $query->execute([$pointArret['id']]);
    $hebergements = $query->fetchAll();
    
    // Stockez les hébergements avec leur point d'arrêt correspondant
    $pointArret['hebergements'] = $hebergements;
}

// Ensuite, dans votre interface utilisateur, vous pouvez afficher les points d'arrêt et les hébergements disponibles pour que l'utilisateur puisse créer son itinéraire.
