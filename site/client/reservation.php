<?php
include 'connection.php';
session_start();

// Après avoir sélectionné un point d'arrêt, récupérez les logements disponibles pour une date donnée:
$date = $_POST['date']; // Exemple de date récupérée depuis un formulaire
$point_arret_id = $_POST['point_arret_id']; 

$sql = "SELECT * FROM logements 
        WHERE point_arret_id = :point_arret_id 
        AND id NOT IN (
            SELECT logement_id FROM reservations WHERE date_reservation = :date
        )";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':point_arret_id', $point_arret_id);
$stmt->execute();

$logements_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ici, affichez $logements_disponibles dans votre interface utilisateur pour que l'utilisateur puisse choisir un logement
