<?php
session_start();
include 'Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $points_arret = $_POST['points_arret'] ?? [];

    if (!$nom || !$date_debut || !$date_fin || !count($points_arret)) {
        echo "Toutes les données requises ne sont pas fournies.";
        exit;
    }

    $user_id = 1; // Assurez-vous que cet ID existe dans la table 'users'

    try {
        $conn->beginTransaction();


        $stmt = $conn->prepare("INSERT INTO itineraire (, nom, date_debut, date_fin) VALUES ( :nom, :date_debut, :date_fin)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->execute();

        $itineraire_id = $conn->lastInsertId();

        foreach ($points_arret as $point_arret_id) {
            $stmt = $conn->prepare("INSERT INTO packs_points_arret (pack_id, point_arret_id) VALUES (:pack_id, :point_arret_id)");
            $stmt->bindParam(':pack_id', $itineraire_id);
            $stmt->bindParam(':point_arret_id', $point_arret_id);
            $stmt->execute();
        }

        $conn->commit();

        echo "Itinéraire créé avec succès!";
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Veuillez soumettre le formulaire.";
}
?>
