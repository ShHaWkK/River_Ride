<?php
session_start(); // Ajout de cette ligne pour démarrer la session
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(!isset($_SESSION['user_id'])) {
        die("L'utilisateur n'est pas connecté");
    }

    $userId = $_SESSION['user_id'];

    $itineraireNom = $_POST['itineraire_nom'];
    $dateDebut = $_POST['date_debut'];
    $dateFin = $_POST['date_fin'];
    $selectedPoints = $_POST['points'];

    $query = "INSERT INTO itineraire (user_id, nom, date_debut, date_fin) VALUES (:user_id, :nom, :date_debut, :date_fin)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':nom', $itineraireNom);
    $stmt->bindParam(':date_debut', $dateDebut);
    $stmt->bindParam(':date_fin', $dateFin);
    $stmt->execute();

    $itineraireId = $conn->lastInsertId();

    foreach ($selectedPoints as $pointId) {
        $query = "INSERT INTO itineraire_points_arret (itineraire_id, point_arret_id) VALUES (:itineraire_id, :point_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':itineraire_id', $itineraireId);
        $stmt->bindParam(':point_id', $pointId);
        $stmt->execute();
    }

    echo "Itinéraire créé avec succès!";
}
?>
