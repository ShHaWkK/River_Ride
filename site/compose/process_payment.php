<?php
include 'Connection.php'; 
require 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

\Stripe\Stripe::setApiKey('sk_test_51NibFdIp7e1YN1xNrQgpq1L3fGTUWXq0ZGLiFdhKQX8hShChXdVQmnJXqF5NM9LTeYHAPTHrrykNH3XtSXF6NQ2R00CYN4wjqD');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stripeToken'])) {
    $token = $_POST['stripeToken'];
    $amount = $_POST['total_amount'];

    try {
        // Stripe Payment
        $charge = \Stripe\Charge::create([
            'amount' => $amount * 100,  // en centimes
            'currency' => 'eur',
            'description' => 'Paiement pour réservation',
            'source' => $token,
        ]);

        if ($charge->paid) {
            // PHPMailer
            $mail = new PHPMailer(true);
            
            $logement_id = $_POST['logement_id'];
            $date_debut = $_POST['date_debut'];
            $nombre_nuits = $_POST['nombre_nuits'];
            $email = $_POST['email'];
            $prenom = $_POST['prenom'];

            $stmt = $conn->prepare("SELECT * FROM logements WHERE id = ?");
            $stmt->execute([$logement_id]);
            $logement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$logement) {
                throw new Exception("Logement non trouvé");
            }

            $prix_par_nuit = $logement['prix_normal'];
            $prix_total = $prix_par_nuit * $nombre_nuits;

            // Mail settings
            $mail->SMTPDebug = 0; 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'riverride573@gmail.com';
            $mail->Password = 'usywmwwlkehtdbbw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('riverride573@gmail.com', 'RiverRide');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de réservation';
            $mail->Body = "<p>Merci $prenom pour votre réservation!</p>
            <p>Voici les détails de votre réservation :</p>
            <p>Date de début: $date_debut</p>
            <p>Nombre de nuits: $nombre_nuits</p>
            <p>Mode de paiement: Carte bancaire</p>
            <p>Prix total: €" . number_format($prix_total, 2) . "</p>";

            $mail->send();
            
            header('Location: confirmation.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Erreur lors du paiement.";
            header('Location: logement.php');
            exit;
        }
    } catch (\Stripe\Error\Card $e) {
        $_SESSION['error_message'] = "Erreur lors du paiement: " . $e->getMessage();
        header('Location: logement.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
        header('Location: logement.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = "Données du formulaire non reçues correctement";
    header('Location: logement.php');
    exit;
}