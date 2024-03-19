<?php
session_start();

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

require 'vendor/autoload.php'; // Inclure l'autoloader de PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

$message = "";

if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour voir la confirmation");
}

$reservation_id = null;
if (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
} else {
    header("Location: reservation.php");
    exit;
}

// Récupérer les détails de la réservation depuis la base de données
$stmtReservation = $conn->prepare("SELECT * FROM reservations WHERE id = :reservation_id AND user_id = :user_id");
$stmtReservation->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
$stmtReservation->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtReservation->execute();
$reservation = $stmtReservation->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("Location: reservation.php");
    exit;
}

// Envoyer un e-mail de confirmation
$email = $_SESSION['email'];
$mail = new PHPMailer(true);
try {
    // Configuration du serveur SMTP et autres paramètres (à personnaliser)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'riverride573@gmail.com';
    $mail->Password = 'usywmwwlkehtdbbw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Paramètres de l'e-mail
    $mail->setFrom('riverride573@gmail.com', 'RiverRide');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de Réservation';

    // Contenu de l'e-mail
    $message = "Votre réservation a bien été confirmée. Voici les détails de votre réservation: <br><br>";
    $message .= "Numéro de Réservation : " . $reservation['id'] . "<br>";
    $message .= "Date de Début : " . $reservation['date_debut'] . "<br>";
    $message .= "Date de Fin : " . $reservation['date_fin'] . "<br>";
    // Ajoutez d'autres détails de la réservation ici

    $message .= "<br>Merci de votre confiance!";
    $mail->Body = $message;

    // Envoyer l'e-mail
    $mail->send();
} catch (Exception $e) {
    echo "L'e-mail n'a pas pu être envoyé. Erreur: " . htmlspecialchars($mail->ErrorInfo);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de Réservation</title>
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
            background-color: #f9c9c3;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
            color: #a82515;
            text-align: center;
        }

        .confirmation-details {
            background-color: #fff;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .confirmation-details h2 {
            color: #2e5d34;
        }

        .confirmation-details p {
            margin: 10px 0;
        }

        .confirmation-back {
            text-align: center;
            margin-top: 20px;
        }
        .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background-color: #2e5d34;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
}
.btn-back:hover {
    background-color: #1f4a29;
    color: #fff;
}
    </style>
</head>
<meta charset="UTF-8">

<body>
    <div class="container">
        <h1>Confirmation de Réservation</h1>
        <?php if(isset($message) && $message): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>
        
        <div class="confirmation-details">
            <h2>Merci de votre réservation!</h2>
            <p>Votre réservation a été confirmée avec succès.</p>
            <p>Vous avez reçu un e-mail de confirmation de votre réservation à l'adresse e-mail que vous avez fournie.</p>

        </div>

        <div class="confirmation-back">
    <p><a href="../index.php" class="btn-back">Retour à l'accueil</a></p>
    </div>

    </div>
</body>
</html>
