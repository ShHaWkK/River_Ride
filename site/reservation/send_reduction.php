<?php
session_start();

include 'Connection.php';

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (!isset($_SESSION['user_id'])) {
    echo "Session expirée ou utilisateur non connecté.";
    exit;
}

if ($firstReservation) { // Utilisez la variable $firstReservation définie dans le fichier reservation.php
    // Générer un code de réduction aléatoire
    $discountPercentage = mt_rand(10, 80);
    $discountCode = generateRandomCode(); // Assurez-vous que cette fonction génère un code unique

    // Enregistrer le code de réduction dans la base de données
    $stmtSaveDiscountCode = $conn->prepare("INSERT INTO codes_reduction (code, pourcentage_reduction) VALUES (:code, :pourcentage_reduction)");
    $stmtSaveDiscountCode->bindParam(':code', $discountCode, PDO::PARAM_STR);
    $stmtSaveDiscountCode->bindParam(':pourcentage_reduction', $discountPercentage, PDO::PARAM_INT);
    $stmtSaveDiscountCode->execute();

    $discountApplied = true;

    // Envoyer le code de réduction par e-mail
    try {
        $mail = new PHPMailer(true);

        $$mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'riverride573@gmail.com';
        $mail->Password = 'usywmwwlkehtdbbw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Port = 587;

        $mail->setFrom('riverride573@gmail.com', 'River Ride');
        $mail->addAddress($_SESSION['user_email'], $_SESSION['user_prenom']);

        $mail->isHTML(true);
        $mail->Subject = 'Votre code de réduction pour votre première réservation';
        $mail->Body = "Bonjour " . $_SESSION['user_prenom'] . ",<br><br>";
        $mail->Body .= "Félicitations pour votre première réservation chez nous!<br>";
        $mail->Body .= "Utilisez le code de réduction suivant pour obtenir une réduction de " . $discountPercentage . "% sur votre réservation:<br><br>";
        $mail->Body .= "Code de réduction: " . $discountCode . "<br><br>";
        $mail->Body .= "Nous espérons que vous passerez un excellent séjour!<br><br>";
        $mail->Body .= "L'équipe River Ride";

        $mail->send();
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'e-mail : " . $e->getMessage();
    }
}

// ...
?>
