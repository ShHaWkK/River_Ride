<?php
include 'Connection.php';
include 'vendor/autoload.php'; // Composer

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['reservation_id']) && !empty($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);
} elseif (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
} else {
    die("ID de réservation non fourni");
}

$reservation_id = $_GET['reservation_id'];

$message = ""; 

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM river_ride.users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user['has_previous_reservations'] == 0 && is_null($user['code_de_reduction'])) {
        $discountCode = bin2hex(random_bytes(5));

        $stmt = $conn->prepare("UPDATE river_ride.users SET code_de_reduction = :code WHERE email = :email");
        $stmt->execute(['code' => $discountCode, 'email' => $email]);
    
    // Envoyer le code de réduction par e-mail
    $mail = new PHPMailer(true);

    try {
        // Configurations
        $mail->SMTPDebug = 3;  // Désactiver la sortie de débogage
        $mail->isSMTP();                                     
        $mail->Host       = 'smtp.gmail.com';                 
        $mail->SMTPAuth   = true;                             
        $mail->Username   = 'riverride573@gmail.com';         
        $mail->Password   = 'usywmwwlkehtdbbw';              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->CharSet    = 'UTF-8';
        $mail->Encoding   = 'base64';
        $mail->Port       = 587;                              

        // Destinataires
        $mail->setFrom('riverride573@gmail.com', 'River Ride');
        $mail->addAddress($email);                           

        // Contenu du courrier électronique
        $mail->isHTML(true);                                 
        $mail->Subject = 'Votre code de réduction River Ride';
        $mail->Body    = "Félicitations! Votre code de réduction est : <b>$discountCode</b>";

        $mail->send();
        $message = "Code de réduction envoyé par e-mail!";
    } catch (Exception $e) {
        $message = "Le message n'a pas pu être envoyé. Erreur de messagerie: {$mail->ErrorInfo}";
    }
} else {
    $message = "Désolé, vous avez déjà utilisé votre offre de première réservation ou vous avez déjà un code de réduction.";
}


if (isset($_POST['code_de_reduction'])) {
    $code = $_POST['code_de_reduction'];

    $stmt = $conn->prepare("SELECT * FROM river_ride.users WHERE email = :email AND code_de_reduction = :code");
    $stmt->execute(['email' => $email, 'code' => $code]);
    $user = $stmt->fetch();

    if ($user && $user['has_previous_reservations'] == 0) {
        // Le code est correct et c'est la première réservation.
        // Ajoutez la logique pour appliquer le rabais ou effectuer d'autres actions ici.
    } else {
        // Redirection vers le mode de paiement si le code est incorrect ou déjà utilisé
        header('Location: mode_paiement.php');
        exit;
    }
}
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Réserver un Pack - Étape 2</title>
</head>
<body>
<div class="container">
    <h1>Réserver un Pack - Étape 2</h1>

    <?php if (isset($message)) {
        echo "<p>$message</p>";
    } ?>

    <?php if (!isset($email)): ?>
        <form action="reservation_etape2.php?reservation_id=<?php echo $reservation_id; ?>" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>
            <input type="submit" value="Obtenir le code de réduction">

        </form>
    <?php elseif (isset($user) && $user['has_previous_reservations'] == 0 && !is_null($user['code_de_reduction'])): ?>
        <form action="reservation_etape3.php?reservation_id=<?php echo $reservation_id; ?>" method="post">
            <label for="code">Entrez votre code de réduction:</label>
            <input type="text" name="code_de_reduction" required><br><br>
            <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">

            <input type="submit" value="Appliquer le code">    
        </form>

        <a href="reservation_etape3.php?reservation_id=<?= $reservation_id ?>">Poursuivre sans code de réduction</a>

    <?php endif; ?>
</div>
</body>
</html>

