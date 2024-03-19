<?php
include 'Connection.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_POST['logements'])) {
    header("Location: composer_itinerary.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Créer une nouvelle entrée dans la table itineraire
$stmt = $conn->prepare("INSERT INTO itineraire (user_id, nom, date_debut, date_fin) VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY))");
$stmt->execute([$user_id, 'Nom d\'Itinéraire']);
$itineraire_id = $conn->lastInsertId();

// Associer les logements choisis à l'itinéraire
foreach ($_POST['logements'] as $point_arret_id => $logement_id) {
    $stmt = $conn->prepare("INSERT INTO itineraire_logements (itineraire_id, point_arret_id, logement_id) VALUES (?, ?, ?)");
    $stmt->execute([$itineraire_id, $point_arret_id, $logement_id]);
}

header("Location: view_itinerary.php");
exit;
?>
