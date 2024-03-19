<?php
session_start();

include 'Connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


$message = "";

if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour voir la confirmation de réservation");
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$reservation_id = null;
if (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
} else {
    die("ID de réservation non fourni");
}

// Récupérer les détails de la réservation depuis la base de données
$stmtGetReservation = $conn->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
$stmtGetReservation->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
$stmtGetReservation->execute();
$reservation = $stmtGetReservation->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Réservation introuvable");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de la Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3faf3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #cde8d6;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #2e5d34;
        }

        .message {
            background-color: #cde8d6;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Confirmation de la Réservation</h1>
    <?php if(isset($message) && $message): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <h2>Merci <?= $_SESSION['user_prenom'] ?> pour votre réservation!</h2>
    <p>Voici les détails de votre réservation :</p>
    
    <p>Date de début: <?= $reservation['date_debut'] ?></p>
    <p>Date de fin: <?= $reservation['date_fin'] ?></p>
    <p>Nombre d'adultes: <?= $reservation['nombre_adultes'] ?></p>
    <p>Nombre d'enfants: <?= $reservation['nombre_enfants'] ?></p>
    <p>Mode de paiement: <?= $reservation['mode_paiement'] ?></p>
    
    <?php if(!empty($reservation['code_de_reduction'])): ?>
        <p>Code de réduction appliqué: <?= $reservation['code_de_reduction'] ?></p>
    <?php endif; ?>

    <p>Nous vous souhaitons un excellent séjour!</p>
</div>
</body>
</html>
