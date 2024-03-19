<?php
// reserver.php

// Commencer la session
session_start();

// Afficher le contenu de $_SESSION
var_dump($_SESSION);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die('Vous devez être connecté pour effectuer cette action.');
}

// Connexion à la base de données
$db = new PDO('mysql:host=localhost;dbname=river_ride', 'root', 'root');

// Créer une nouvelle réservation
$query = $db->prepare('INSERT INTO reservations (user_id, date_reservation) VALUES (?, NOW())');
$query->execute([$_SESSION['user_id']]);
$reservationId = $db->lastInsertId();

// Réserver l'hébergement
$query = $db->prepare('INSERT INTO reservations_hebergements (reservation_id, hebergement_id, nb_personnes) VALUES (?, ?, ?)');
$query->execute([$reservationId, $_POST['hebergement_id'], 1]);  // Assume 1 person for simplicity

echo "Hébergement réservé avec succès!";
