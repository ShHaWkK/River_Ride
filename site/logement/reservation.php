<?php
session_start();
require 'Connection.php';

if (!isset($_POST['logement_id']) || !isset($_SESSION['point_arret_id']) || !isset($_SESSION['nombre_personnes'])) {
    die("Informations manquantes pour la réservation.");
}

$logement_id = $_POST['logement_id'];
$point_arret_id = $_SESSION['point_arret_id'];
$nombre_personnes = $_SESSION['nombre_personnes'];

// Récupération des détails du logement
$stmt = $conn->prepare("SELECT * FROM river_ride.logements WHERE id = :logement_id");
$stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
$stmt->execute();
$logement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$logement) {
    die("Logement introuvable.");
}

if ($logement['capacite'] < $nombre_personnes || $logement['disponibilite'] <= 0) {
    die("Ce logement n'est pas disponible.");
}

// Insertion d'une nouvelle réservation (hypothétiquement - vous aurez besoin de créer une table de réservation ou d'ajouter une logique de déduction des disponibilités)
// $stmt = $conn->prepare("INSERT INTO river_ride.reservations (logement_id, point_arret_id, nombre_personnes) VALUES (:logement_id, :point_arret_id, :nombre_personnes)");
// $stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
// $stmt->bindParam(':point_arret_id', $point_arret_id, PDO::PARAM_INT);
// $stmt->bindParam(':nombre_personnes', $nombre_personnes, PDO::PARAM_INT);
// $stmt->execute();

// Mettre à jour la disponibilité du logement
$stmt = $conn->prepare("UPDATE river_ride.logements SET disponibilite = disponibilite - 1 WHERE id = :logement_id");
$stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
$stmt->execute();

// Redirection basée sur les conditions précédentes
if (!$user['has_previous_reservations'] && !$user['code_de_reduction']) {
    header('Location: discount_code.php');
    exit;
} else {
    header('Location: mode_paiement.php');
    exit;
}
?>
