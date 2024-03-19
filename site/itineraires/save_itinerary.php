<?php
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['start_point_id']) || !isset($_POST['end_point_id'])) {
    header("Location: compose_itinerary.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO itineraire (user_id, nom, date_debut, date_fin, start_point_id, end_point_id) VALUES (?, 'Nom d\'Itinéraire', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), ?, ?)");
$stmt->execute([$user_id, $_POST['start_point_id'], $_POST['end_point_id']]);

header("Location: view_itinerary.php");
exit;
