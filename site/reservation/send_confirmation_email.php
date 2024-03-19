<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'Connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Session expirée ou utilisateur non connecté.";
    exit;
}

$reservation_id = $_SESSION['reservation_id']; // Récupérez l'ID de réservation depuis la session


// Récupérer les détails de la réservation depuis la base de données en fonction de $reservation_id
$stmt = $conn->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
$stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);


$email = $_SESSION['email']; // Récupérez l'adresse e-mail depuis la session

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'riverride573@gmail.com';
    $mail->Password = 'usywmwwlkehtdbbw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = 587;

    $mail->setFrom('riverride573@gmail.com', 'RiverRide');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de Réservation';
    $reservation_id = $_SESSION['reservation_id'];

    // Récupérer les détails de la réservation depuis la base de données en fonction de $reservation_id
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $_SESSION['email'];
    $message = "Votre réservation a bien été confirmée. <br><br>";
    $message .= "Merci de votre confiance!";
    $mail->Body = $message;
    $mail->send();

} catch (Exception $e) {
    echo "L'e-mail n'a pas pu être envoyé. Erreur: " . htmlspecialchars($mail->ErrorInfo);
}
?>
