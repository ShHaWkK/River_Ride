<?php
// Inclure votre fichier de connexion à la base de données ici
include('Connection.php');

// Récupérer les points d'arrêt depuis la base de données
$stmt = $conn->prepare('SELECT * FROM points_arret');
$stmt->execute();
$pointsArret = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire de création de pack
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $points_arrêt = $_POST['points_arret'];

    // Ajouter ici le code pour insérer les données du pack dans la base de données

    // Rediriger vers une page de confirmation ou une autre page appropriée
    header('Location: confirmation.php');
    exit();
}
?>
